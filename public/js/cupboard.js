function addItem(ingredientType) {
    const itemName = $("#item-name-" + ingredientType).val();
    const itemAmount = $("#item-amount-" + ingredientType).val();
    if (!itemName.trim()) {
        alert("Please add item name");
        return;
    }
    if (!itemAmount.trim()) {
        alert("Please add item amount");
        return;
    }
    $.post('cupboard/add', {
        'name': itemName,
        'amount': itemAmount,
        'ingredientType': ingredientType},
        function() {
            $("#item-name-" + ingredientType).val("");
            $("#item-amount-" + ingredientType).val("");
             $.get("/cupboard/gettable", {'ingredientType': ingredientType}, function(html) {
             $("#table-" + ingredientType).html(html);
         });
     });
}
