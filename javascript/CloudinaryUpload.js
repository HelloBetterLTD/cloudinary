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
                var self = this;
                var id = $(this).val();
                var holder = self.closest('div.cloudinaryupload');
                if (id) {
                    $.ajax({
                        url         : this.data('url') + '/getinfo',
                        data        : {
                            cloudinary_id : id
                        },
                        method      : 'POST',
                        type        : 'POST',
                        dataType    : 'json',
                        success     : function(data) {

                            holder.find("input[name*='width']").val(data.width);
                            holder.find("input[name*='height']").val(data.height);
                            holder.find("input[name*='format']").val(data.format);
                            holder.find("input[name*='size']").val(data.bytes);
                            holder.find("input[name*='resource_type']").val(data.resource_type);
                            holder.find('._js-additional-fields').show();
                        }


                    });

                }

            }

        });


    });


})(jQuery);