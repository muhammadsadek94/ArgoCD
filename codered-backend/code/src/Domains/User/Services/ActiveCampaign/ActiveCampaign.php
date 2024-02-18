<?php

namespace App\Domains\User\Services\ActiveCampaign;

class ActiveCampaign extends \ActiveCampaign
{

    function __construct($url, $api_key, $api_user = "", $api_pass = "", $track_email = "", $track_actid = "", $track_key = "") {
        $this->url_base = $this->url = $url;
        $this->api_key = $api_key;
        $this->track_email = $track_email; //an default email will receive events
        $this->track_actid = $track_actid;
        $this->track_key = $track_key;
        parent::__construct($url, $api_key, $api_user, $api_pass);

    }

    function api($path, $post_data = array()) {
        // IE: "contact/view"
        $components = explode("/", $path);
        $component = $components[0];

        if (count($components) > 2) {
            // IE: "contact/tag/add?whatever"
            // shift off the first item (the component, IE: "contact").
            array_shift($components);
            // IE: convert to "tag_add?whatever"
            $method_str = implode("_", $components);
            $components = array($component, $method_str);
        }

        if (preg_match("/\?/", $components[1])) {
            // query params appended to method
            // IE: contact/edit?overwrite=0
            $method_arr = explode("?", $components[1]);
            $method = $method_arr[0];
            $params = $method_arr[1];
        }
        else {
            // just a method provided
            // IE: "contact/view
            if ( isset($components[1]) ) {
                $method = $components[1];
                $params = "";
            }
            else {
                return "Invalid method.";
            }
        }

        // adjustments
        if ($component == "list") {
            // reserved word
            $component = "list_";
        }
        elseif ($component == "branding") {
            $component = "design";
        }
        elseif ($component == "sync") {
            $component = "contact";
            $method = "sync";
        }
        elseif ($component == "singlesignon") {
            $component = "auth";
        }

        $class = ucwords($component); // IE: "contact" becomes "Contact"
        $class = "AC_" . $class;
        // IE: new Contact();

        $add_tracking = false;
        if ($class == "AC_Tracking") $add_tracking = true;
        if ($class == "AC_Tags") {
            $class = "AC_Tag";
        }

        $class = new $class($this->version, $this->url_base, $this->url, $this->api_key);
        // IE: $contact->view()

        if ($add_tracking) {
            $class->track_email = $post_data['email'] ?? $this->track_email;
            $class->track_actid = $this->track_actid;
            $class->track_key = $this->track_key;
        }

        if ($method == "list") {
            // reserved word
            $method = "list_";
        }

        $class->debug = $this->debug;

        $response = $class->$method($params, $post_data);
        return $response;
    }

}
