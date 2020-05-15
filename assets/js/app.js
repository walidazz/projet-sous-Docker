/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "../css/app.scss";

var $ = require("jquery");

global.$ = global.jQuery = $;

require("bootstrap");
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';



// <a href="{{path('admin_comment_delete', {'id' : comment.id})}}" data-action="delete"
//   data-target="#block_"><i class="fas fa-trash mx-1"></i></a>


$(document).ready(function () {
  $('[data-action="delete"]').on("click", function () {
    const target = this.dataset.target;
    $(target).remove();
  });
});
