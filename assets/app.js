/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import './styles/style.scss';
import './styles/poncho.css';
import './styles/style.css';

//import './styles/index.css';
//import './styles/docs.min.css';

import $ from 'jquery';
import jQuery from 'jquery';
global.$ = global.jQuery = $;

import './bootstrap';

import validator from 'validator';
global.validator = validator;
