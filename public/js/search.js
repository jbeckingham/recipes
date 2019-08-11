function SearchViewModel() {
    var self = this;

    // Editable data
    self.searchItems = ko.observableArray([
    ]);


    // Operations
    self.addItemToSearch = function(data, event) {
        if (event.target.classList.contains("ingredient-selected")) {
            var index = self.searchItems.indexOf(event.target.innerHTML);    // <-- Not supported in <IE9
            if (index !== -1) {
                self.searchItems.splice(index, 1);
            }
            event.target.classList.remove("ingredient-selected");
        }
        else {
            self.searchItems.push(event.target.innerHTML);
            event.target.classList.add("ingredient-selected")
        }
    }

    self.searchRecipes = function(data, event) {
        if (self.searchItems().length > 0) {
            var url = "https://www.google.com/search?q=" + self.searchItems().concat("recipes").join("+");
            window.open(url);
            $(".search-error").css("display", "none");
        }
        else {
            $(".search-error").css("display", "block");
        }
    }

    // Computed data
    self.searchItemsList = ko.computed(function() {
        var itemsHtml = self.searchItems().join(" ");
        if (self.searchItems().length > 0) {
            itemsHtml += " recipes";
        }
        return itemsHtml;
    });
}

ko.applyBindings(new SearchViewModel());
