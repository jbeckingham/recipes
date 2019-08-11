let additionalIngredientId = 0;

function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}

function addAnotherIngredient() {
    additionalIngredientId++;
    var innerHTML = '<div id="ingredient-'+additionalIngredientId+'" class="ingredient-input"><input type="text" class="add-ingredient" placeholder="Enter Ingredient"><input type="text" class="add-ingredient-amount" placeholder="Enter Amount"><button type="button" class="close crossicon" onclick="removeAdditionalIngredient('+additionalIngredientId+')"><span aria-hidden="true">&times;</span></button></div>';
    document.getElementById('add-ingredients-input').innerHTML += innerHTML;
}

function removeAdditionalIngredient(id) {
    $("#ingredient-" + id).hide();
}

function addRecipe() {
    var name = $("#recipeName").val();
    var type = $("#recipeType").val();
    var serves = $("#recipeServes").val();
    var time = $("#recipeTime").val();
    var method = $("#recipeMethod").val();
    var ingredientArray = $(".ingredient-input");

    var ingredients = [];
    for (ingredient  of ingredientArray) {
        var item = {};
        item['ingredient'] = ingredient.getElementsByClassName("add-ingredient")[0].value;
        item['amount'] = ingredient.getElementsByClassName("add-ingredient-amount")[0].value;
        ingredients.push(JSON.stringify(item));
    }

    $.post('recipe/add', {'name': name, 'type': type, 'serves': serves, 'time': time, 'method': method, 'ingredients': JSON.stringify(ingredients)}, function() {
        location.reload();
    });
}

function deleteRecipe(recipeid) {
    $.post('recipe/delete', {'id': recipeid}, function() {
        location.reload();
    });
}

var modal = document.getElementById('myModal');

function showIngredients(recipeId){
    $.post('recipe/ingredients', {'recipeId': recipeId}, function(html) {
        $(".modal-content").html(html);
        modal.style.display = "block";

    });
}

function hideModal(){
    modal.style.display = "none";
}

function showMethod(recipeId){
    $.post('recipe/method', {'recipeId': recipeId}, function(html) {
        $(".modal-content").html(html);
        modal.style.display = "block";
    });}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
