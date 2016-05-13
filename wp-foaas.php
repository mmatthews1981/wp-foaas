<?php

/*
Plugin Name: WP-FOAAS
Plugin URI: https://github.com/mmatthews1981/wp-foaas
Description: WP-FOAAS provides shortcode access to the Fuck Off As A Service API
Version: 0.1
Author: m.matthews
Author URI: https://github.com/mmatthews1981
License: GPL2
*/

include( plugin_dir_path( __FILE__ ) . 'struct.php');

function foaas_func($atts){
    //set the attrib defaults
    $atts = shortcode_atts($atts, 'foaas' );

    if(!array_key_exists("command",$atts)) {return 'You must specify a command attribute.'; exit(); }

    //The author of this plugin is an SWJ and disagrees with some decisions FOAAS has made.
    if( $atts['command'] && $atts['command'] === 'retard') {
        return 'The person who wrote this entry is an asshole.'; exit();
    }

    //callback, encode atts for the foaas api
    function mapurlencodefunc($str){
      return urlencode($str);
    };

    // make the $atts ready for transition
    $newarr = array_map('mapurlencodefunc', $atts);
    //build the api URL string
    $apiurl = 'http://foaas.com/'.implode('/', $newarr);
    // get the output
    $final = getthejson($apiurl);

    //if the output is a 622 error code, find the required shortcode atts and return an error code
    function requiredattributes($command){
        $structure = getthejson('http://foaas.com/operations/');
        $str = ucfirst(strtolower($command));
        foreach($structure as $thing){
            if($thing['name'] === $str || $thing['name'] === strtolower($str)) {
                $thepath = explode("/:",$thing['url']);
                array_shift($thepath);
            }
        }
        return 'The shortcode for command '.$command.' requires the following atributes to work: '.implode(', ', $thepath);
    }
    //return an error code
    if($final['message'] == '622 - All The Fucks'){ return requiredattributes($atts['command']); exit();}

    //return the quote
    return '<span class="message">'.urldecode($final['message']).'</span><span class="subtitle">'.urldecode($final['subtitle']).'</span>';
}

function foaas_func_custom_admin_menu() {
    add_options_page(
        'WP_FOAAS',
        'WP_FOAAS Settings',
        'manage_options',
        'wp_foaas',
        'wp_foaas_options_page'
    );
}

function wp_foaas_options_page() {

    $reference = getthejson('http://foaas.com/operations/');

    ?>
    <div class="wrap">
        <h2>WP Fuck Off As A Service</h2>
        <p>FOAAS (Fuck Off As A Service) provides a modern, RESTful, scalable API solution to the common problem of telling people to fuck off, and now it's availabe to you via WordPress shortcodes!</p>

        <h3>Instructions</h3>
        <p>To insert a dynamic fuckoff into your post or page is easy: just pick a fuckoff from the list below, and fill out the shortcode madlibs style. TIP: If you want to use more than one word in your fields, just put them in quotes.</p>

        <h3>Examples</h3>
        <blockquote>
        <h4>fuckoff</h4>
        <div>Fuck Off	/off/:name/:from</div>
        <h4>Shortcode</h4>
        <p>[foaas command=off name=Bob from=Bill]</p>
        <h4>Result</h4>
        <p>Fuck off, Bob. - Bill</p>
        </blockquote>

        <blockquote>
        <h3>Examples</h3>
        <h4>fuckoff</h4>
        <p>Do Something	/dosomething/:do/:something/:from</p>
        <h4>Shortcode</h4>
        <p>[foaas command=dosomething do=mow something=grass from='Your Wife']</p>
        <h4>Result</h4>
        <p>mow the fucking grass! - Your Wife</p>
        </blockquote>

        <blockquote>
        <h3>Examples</h3>
        <h4>fuckoff</h4>
        <p>Keep Calm	/keepcalm/:reaction/:from</p>
        <h4>Shortcode</h4>
        <p>[foaas command=keepcalm reaction='bake the cake' from=Margaret]</p>
        <h4>Result</h4>
        <p>Keep the fuck calm and bake the cake! - Margaret</p>
        </blockquote>

        <h3>Credits</h3>
        <p><a href="https://twitter.com/foaas">Follow @foaas on Twitter</a></p>
        <p>Plugin implemented by <a href="https://github.com/mmatthews1981">m.matthews</a></p>
        <p>FOAAS API created by <a href="https://twitter.com/TomDionysus">@TomDionysus</a> and maintained with loving Profanity by <a href="https://twitter.com/philip2156">@philip2156</a>, <a href="https://twitter.com/chris_beckett">@chris_beckett</a></p>

        <h3>Available Shortcodes</h3>
        <table>
            <?php
            foreach($reference as $item){
                echo '<tr><td>'.$item['name'].'</td><td>'.$item['url'].'</td></tr>';
            }
            ?>
        </table>
    </div>
    <?php
}

add_shortcode('foaas', 'foaas_func');
add_action( 'admin_menu', 'foaas_func_custom_admin_menu' );