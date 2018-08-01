<?php


//Route::get("user/{id}", "UsersController->get_user");

//Route::get("test", "UsersController->test");

//ROUTE::get("user/{id}/{name}/{surname}", "UsersController->get_users");

Route::get("/user/.+/.+", function () {
    echo "yeah";
});

Route::get('/user/.+/', "UsersController:get_user");






