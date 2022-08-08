/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';
import bsCustomFileInput from 'bs-custom-file-input';
import tinymce from 'tinymce';
import 'tinymce/themes/silver/theme';
import 'tinymce/models/dom/model';
import 'tinymce/icons/default/icons';
import 'tinymce/skins/ui/oxide/skin.min.css';
import 'tinymce/skins/ui/oxide/content.min.css';
import 'tinymce/skins/content/default/content.css';


tinymce.init({
    'selector': '.tinymce'
});
bsCustomFileInput.init();