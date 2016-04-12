(function($){

    $.entwine('ss', function($){

        $('._js-upload-area').entwine({

            onmatch: function () {

                var holder = $(this);

                var input = holder.find('input.cloudinaryupload');

                $(document).unbind('dragover');
                holder.bind('dragover', function (e) {
                    timeout = window.cloudinaryTimeout;
                    var $target = $(e.target);
                    holder.addClass('active');
                    if (!timeout) {
                        holder.addClass('active');
                    } else {
                        clearTimeout(timeout);
                    }
                    window.cloudinaryTimeout = setTimeout(function () {
                        window.cloudinaryTimeout = null;
                        holder.removeClass('active hover');
                    }, 100);
                });


                $(document).bind('drop dragover', function (e) {
                    e.preventDefault();
                });

                holder.bind('drop', function (e) {

                    console.log(e);

                    e.preventDefault();

                });


            },

        });

        $('input._js-upload-area').entwine({

            onkeydown : function(e) {
                if(e.keyCode == 13){
                    this.urlChanged();
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
                return true;
            },

            urlChanged: function() {
                var id = $(this).val();
                if (id) {
                    $.ajax({
                        url         : this.data('url') + 'getinfo',
                        data        : {
                            cloudinary_id : id
                        },
                        method      : 'POST',
                        type        : 'POST',
                        dataType    : 'json',
                        success     : function(data) {
                            console.log(data);
                        }


                    });

                    /*
                    this.data('url') + 'getinfo', {

                    }, function(data) {

                    });
                    */
                }

            }

        });


    });


})(jQuery);