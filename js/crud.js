$(document).ready(function () {
  $(".form-btn").click(function () {
    // Add an item
    event.preventDefault();

    var inputField = $('input[type="text"]');
    var request = {
      name: $(this).prev().val(),
    };

    if (inputField.prop("name") == "new-unit") {
      request.type = "unit";
      request.requestType = "add-new-unit";
    } else if (inputField.prop("name") == "new-food") {
      request.type = "food";
      request.requestType = "add-new-food";
    }

    // check for duplicates
    var currentItems = [];
    $(".content-list ul li").each(function () {
      currentItems.push($(this).text().trim().toLowerCase());
    });

    var alreadyExists = currentItems.includes(
      request.name.trim().toLowerCase()
    );

    if (request.name == "") {
      $(".message").css("display", "block");
      $(".message span").text("Gelieve iets in te geven");
    } else if (alreadyExists) {
      $(".message").css("display", "block");
      $(".message span").text("Dit bestaat al");
    } else {
      $.ajax({
        url: "inc/actionHandler.php",
        method: "POST",
        data: {
          request: request.requestType,
          name: request.name,
        },
        success: function (data, textStatus, jqWHR) {
          $(".message").css("display", "none");
          if (request.type == "unit") {
            $(".content-list ul").append(
              '<li class="units-theme-color">' +
                request.name +
                ' <i class="fa fa-remove remove-btn"></i></li>'
            );
          } else if (request.type == "food") {
            $(".content-list ul").append(
              '<li class="food-theme-color bordered-grey">' +
                request.name +
                ' <i class="fa fa-remove remove-btn"></i></li>'
            );
          } else if (request.type == "recipe") {
            $(".content-list ul").append(
              '<li class="recipe-theme-color">' +
                request.name +
                ' <i class="fa fa-remove remove-btn"></i></li>'
            );
          }
          inputField.val("");
        },
        error: function (jqXHR, textStatus, errorThrown) {
          alert("Iets ging verkeerd.");
        },
      });
    }
  });

  $("ul").on("click", ".remove-btn", function (e) {
    // remove an item
    console.log($(this).parent().parent());
    $(this).parent().parent().removeAttr("href");

    var item = $(this).parent();

    // click event must be bound to parent
    // these elements can be dynamically added
    // click handlers are added at DOM creation
    // the parent exists throughout
    var request = {
      name: $(this).parent().text(),
      id: $(this).parent().attr("id"),
    };

    if ($(this).parent().hasClass("units-theme-color")) {
      request.requestType = "delete-unit-by-name";
    } else if ($(this).parent().hasClass("food-theme-color")) {
      request.requestType = "delete-food-by-name";
    } else if ($(this).parent().hasClass("recipe-theme-color")) {
      request.requestType = "delete-recipe-by-name";
    }

    $.ajax({
      url: "inc/actionHandler.php",
      method: "POST",
      data: {
        request: request.requestType,
        name: request.name,
        id: request.id,
      },
      success: function (data, textStatus, jqWHR) {
        if (data.includes("SQLSTATE[23000]")) {
          console.log(data);
          $(".message").css("display", "block");
          $(".message span").text(
            "Kan " +
              request.name +
              "niet verwijderen (nog aanwezig in bestaande recepten)"
          );
        } else {
          console.log(data);
          item.remove();
          $(".message").css("display", "block");
          $(".message span").text(request.name + " werd verwijderd");
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert("Iets ging verkeerd.");
      },
    });
  });

  $(".recipe-form-container").on(
    "click",
    'input[value="Bewaar recept"]',
    function (e) {
      amountArray = $(".ingredient-amount");
      unitArray = $(".ingredient-unit");
      foodArray = $(".ingredient-food");

      editArray = [amountArray, unitArray, foodArray];
      changeNameAttributeInArray(editArray);
    }
  );

  function changeNameAttributeInArray(elementArray) {
    var ingredientAmountCounter = 1;
    var ingredientUnitCounter = 1;
    var ingredientFoodCounter = 1;

    for (let element of elementArray) {
      for (let htmlElement of element) {
        if (htmlElement.getAttribute("name").includes("amount")) {
          htmlElement.setAttribute(
            "name",
            "ingredient-amount-" + ingredientAmountCounter
          );
          ingredientAmountCounter++;
        } else if (htmlElement.getAttribute("name").includes("unit")) {
          htmlElement.setAttribute(
            "name",
            "ingredient-unit-" + ingredientUnitCounter
          );
          ingredientUnitCounter++;
        } else if (htmlElement.getAttribute("name").includes("food")) {
          htmlElement.setAttribute(
            "name",
            "ingredient-food-" + ingredientFoodCounter
          );
          ingredientFoodCounter++;
        }
      }
    }

    $('input[name="nrOfIngredientLines"]').val(elementArray[0].length);
  }

  $(".edit-recipe").click(function () {
    event.preventDefault();
    $('input[type="text"]').prop("readonly", "");
    $('input[type="file"]').prop("disabled", "");
    $('input[type="number"]').prop("readonly", "");
    $("textarea").prop("readonly", "");
    $("select").prop("disabled", "");
    $(".ingredient-line-btn").removeClass("disabled-line-btn");
    $(this)
      .parent()
      .append(
        '<input type="submit" value="Bewaar recept" class="save-or-edit-recipe remove-standard-btn-styling recipe-theme-color half-width-btn" name="update-recipe">'
      );
    $(this).remove();
  });

  // image size & extension validation
  $(".recipe-form-container").on(
    "click",
    'input[value="Bewaar recept"]',
    function (e) {
      var file = $('input[type="file"]')[0].files[0];
      var errors = [];
      console.log(file);

      if (file.size >= 150 * 1024) {
        errors.push("afbeelding is te groot");
      } else if (
        file.size == 0 ||
        !["image/png", "image/jpg", "image/jpeg", "image/gif"].includes(
          file.type
        )
      ) {
        errors.push("geen geldige afbeelding");
      }

      if (errors.length != 0) {
        e.preventDefault();
        $(".message").css("display", "block");
        $(".message span").text("Fout: " + errors[0]);
      }
    }
  );

  $(".recipe-form-container").on("click", ".recipe-image i", function (e) {
    var item = $(this).parent();
    var request = {
      name: "remove-img",
      id: $('input[name="id"]').val(),
    };

    $.ajax({
      url: "inc/actionHandler.php",
      method: "POST",
      data: {
        name: request.name,
        id: request.id,
      },
      success: function (data, textStatus, jqWHR) {
        item.remove();
        $(".message").css("display", "block");
        $(".message span").text("Afbeelding werd verwijderd");
        console.log($('input[name="existing-image"]'));
        $('input[name="existing-image"]').remove();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert("Iets ging verkeerd.");
      },
    });
  });
});
