$(function() {
    $('.add-menu').click(function() {
        var parent = $(this).parent();
        if(parent.find("ul").length == 0) {
            parent.append("<ul></ul>");
        }
        var list = parent.find("> ul");
        var html = "<li>";
        var n= $("form ul li").length+1;
        html+= '<input type="hidden" name="menu['+n+'][parent]" value="'+parent.find(">.id").val()+'"> ';
        html+= '<input type="text" name="menu['+n+'][name]" value=""> ';
        html+= '<input type="text" name="menu['+n+'][url]" value=""> ';
        html+= '<select name="menu['+n+'][page]"> ';
        var opts = parent.find("> select");
        opts = $(opts[0]).find("option");
        opts.each( function (i,o) { html+= o.outerHTML; });
        html+= '</select> ';
        html+= '<button type="button" class="delete-menu">-</button> ';
        html+= "</li>";
        list.append(html);

        list.find(".delete-menu").click(deleteMenu);
    });

    $(".delete-menu").click(deleteMenu);
});

function deleteMenu() {
    var parent = $(this).parent();
    var id = parent.find(".id");
    id.each( (i, id) => { 
        $("form").append("<input type='hidden' name='delete[]' value='"+$(id).val()+"' />");
    });
    parent.remove();
}
