/* **********

You should put custom input and its label into one parent container, for example:
    <p><input type="checkbox" class="custom" checked><label>Custom checkbox</label></p>

If you don't want label near input to be clicked, you should set option label = "false":
    $("input[type='checkbox']").tiCheckbox({label: "false"});

You can disable your input by adding "disable" attribute.

You can set left or right position for check icon and set margin.

After changing your input you should refresh custom label state:
    $("input[type='checkbox']").tiCheckbox().refresh();

You can use this plugin also for radio buttons and groups. Plugin adds special class "radio" for
custom label container.

******** */
(function ($) {
    $.extend($.fn, {
        tiCheckbox: function(options){

            var defaults = {
                label: "true",
                margin: "10",
                position: "left"
            };
            var options = $.extend(defaults, options);

            this.refresh = function(){
                return this.each(function(){
                    var check = $(this);
                    var label = check.siblings("label");

                    setCheck(check, label);

                    label.bind("click", function(){
                        if (!label.hasClass("disabled")) lbClick(label);
                    });
                });
            }

            return this.each(function(){
                var check = $(this);
                var label;
                if (options.label == "true") {
                    label = check.siblings("label");
                    if (label.length == 0) label = $("<label></label>").insertAfter(check);
                } else {
                    label = $("<label></label>").insertAfter(check);
                }

                function init(){
                    check.addClass("ti-hidden");
                    label.addClass("ti-custom-checkbox");
                    if (check.attr("type")=="radio") label.addClass("radio");
                    if (options.position == "left") label.prepend('<i class="icon-checkbox" style="margin-right: '+options.margin+'px"></i>');
                    else label.append('<i class="icon-checkbox" style="margin-left: '+options.margin+'px"></i>');
                    setCheck(check, label);
                }

                if (!check.hasClass("ti-hidden")) init();

                label.bind("click", function(){
                    if (!label.hasClass("disabled")) {
                        if (!label.attr("for")) check.trigger("click");
                        lbClick(label);
                    }
                });
            });

            function lbClick(lb){
                var cCh = lb.siblings("input.ti-hidden");
                if (cCh.attr("type")=="radio") {
                    $('input[name="'+cCh.attr("name")+'"]').each(function(){
                        $(this).siblings('label').removeClass("checked");
                    });
                    lb.addClass("checked");
                } else {
                    lb.toggleClass("checked");
                };
            }

            function setCheck(ch, lb){
                if (ch.attr("disabled")) lb.addClass("disabled");
                else lb.removeClass("disabled");
                if (ch.attr("checked")) lb.addClass("checked");
                else lb.removeClass("checked");
            }
        }
    });
})(jQuery);
