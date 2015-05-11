<?php
/**
 * Created by IntelliJ IDEA.
 * User: urandu
 * Date: 5/10/15
 * Time: 8:36 PM
 */



function advice($origin,$destination,$route)
{
    if($origin==$destination)
    {
        return "--";
    }
    $CI=get_instance();
    $CI->load->model('stop_model');
    $CI->load->model('route_model');
    $origin_details=$CI->stop_model->stop_details($origin);
    $destination_details=$CI->stop_model->stop_details($destination);
    $route_details=$CI->route_model->route_details($route);
    if($origin_details && $destination_details && $route_details)
    {
        $result="Board Matatu Number: "
            .$route_details->route_short_name.
            " - ".$route_details->route_long_name.
            " At ".$origin_details->stop_name.
            " And alight at ".$destination_details->stop_name.".  ";
        return $result;
    }else
    {
       return false;
    }
}


function route_intersection($route1_id,$route2_id)
{
    $CI=get_instance();
    $CI->load->model('route_model');
    $route1_stops=$CI->route_model->get_route_stops($route1_id);
    $route2_stops=$CI->route_model->get_route_stops($route2_id);
    if($route1_stops && $route2_stops)//query is a success
    {
        $intersect=array_intersect($route1_stops,$route2_stops);
        if($intersect)//intersection exists
        {
            return $intersect;
        }
        else//no intersect exists
        {
            return false;
        }
    }
    else//query unsuccessful return false
    {
        return false;
    }
}

function get_route_town_terminus($route_id)
{
    $CI=get_instance();
    $CI->load->model('route_model');
    return $CI->route_model->route_town_terminus($route_id);
}