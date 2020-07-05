$(document).ready(function () {
  $(".remove-warning-btn").click(function () {
    $(".message").css("display", "none");
  });

  $(".autocomplete").autocomplete({
    source: "",
  });

  $(".autocomplete").keyup(function (e) {
    var currentField = $(this);
    var request = "autocomplete";

    var autoComplete = {
      str: $(this).val(),
    };

    if (currentField.attr("name") === "new-unit") {
      autoComplete.table = "units";
    } else if (currentField.attr("name") === "new-food") {
      autoComplete.table = "food";
    } else if (currentField.attr("name") === "find-recipe") {
      autoComplete.table = "recipe";
    }

    $.ajax({
      method: "POST",
      url: "inc/actionHandler.php",
      data: {
        request: request,
        str: autoComplete.str,
        table: autoComplete.table,
      },
      dataType: "json",
      success: function (data) {
        currentField.autocomplete({
          source: data,
        });
      },
      error: function (data) {
        console.log("Error", data);
      },
    });
  });

  $(".fa-angle-double-down").click(function () {
    $(".content-list li").css("display", "table");
    $(".content-list li").css("width", "");
    $(".content-list li").css("height", "");
    $(".content-list li").css("margin", "");
  });

  $(".fa-angle-double-right").click(function () {
    $(".content-list li").css("display", "inline-block");
    $(".content-list li").css("width", "");
    $(".content-list li").css("height", "");
    $(".content-list li").css("margin", "3px");
  });

  $(".recipe-form-container").on("click", ".add-line", function (e) {
    if (!$(this).hasClass("disabled-line-btn")) {
      var appendToMe = $(".ingredient-line-container").last();
      $.ajax({
        url: "ingredient-line.php",
        success: function (html) {
          appendToMe.after(
            '<div class="ingredient-line-container">' + html + "</div>"
          );
        },
      });
    }
  });

  $(".recipe-form-container").on("click", ".remove-line", function (e) {
    if (
      !$(this).hasClass("disabled-line-btn") &&
      $(".ingredient-info").length >= 2
    ) {
      $(this).parent().parent().remove();
    }
  });

  $(".find-btn").click(function (e) {
    e.preventDefault();
    var request = {
      type: "find-id-by-name",
      str: $(this).prev().val(),
    };
    console.log(request);

    $.ajax({
      method: "POST",
      url: "inc/actionHandler.php",
      data: {
        request: request.type,
        str: request.str,
      },
      dataType: "json",
      success: function (data) {
        console.log(data);
        window.location = "recept.php?id=" + data.id;
      },
      error: function (data) {
        console.log("Error", data);
      },
    });
  });

  $(".recipe-select").select2();
});
