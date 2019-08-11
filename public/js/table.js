function deleteItem(e) {
    let itemid = e.dataset.itemId;
    let ingredientType = e.dataset.ingredientType;
    $.post('cupboard/delete', {'id': itemid}, function() {
        $.get("/cupboard/gettable", {'ingredientType': ingredientType}, function(html) {
            $("#table-"+ingredientType).html(html);
        });
    });
};
