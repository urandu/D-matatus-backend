<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stops extends CI_Controller {


	public function index()
	{
		$this->load->view('welcome_message');
	}
/*
 * get_trip() will be open for API calls\
 * it will return a json object comprising
 *
 *
 *
 * */
    public function get_trip($origin_lat=null,$origin_lon=null,$destination_lat=null,$destination_lon=null)
    {
        $intersection_stop_id=null;
        $origin_route_town_terminal=null;
        $destination_route_town_terminal=null;
        $origin=null;
        $destination=null;
        $destination_route=null;
        $origin_route=null;
        $origin_and_destination_are_on_same_route=null;
        $advice=null;
        $final_route_advice=null;
        $intersection=null;
        if(!empty($origin_lat) && !empty($origin_lon) && !empty($destination_lat) && !empty($destination_lon))//if the received request has data
        {
            $this->load->model('stop_model');
            $origin = $this->stop_model->get_nearest_stop($origin_lat, $origin_lon);
            $destination = $this->stop_model->get_nearest_stop($destination_lat, $destination_lon);
            if ($origin && $destination) { //destination and origin are set
                $this->load->model('route_model');
                $origin_route = $this->route_model->get_route($origin);
                //print_r($origin_route);

                $destination_route = $this->route_model->get_route($destination);

                //print_r($destination_route);
                if ($destination_route && $origin_route) {
                    $origin_and_destination_are_on_same_route = array_intersect($origin_route, $destination_route);
                    //print_r($origin_and_destination_are_on_same_route);
                    if ($origin_and_destination_are_on_same_route) //if stops are on the same route
                    {
                        $origin_route = $origin_and_destination_are_on_same_route[0];
                        $advice = advice($origin, $destination, $origin_route);
                        if ($advice) {
                     //       echo($advice);
                            $final_route_advice=$advice;
                        } else {
                       //     echo(-1);
                            $final_route_advice=null;
                        }
                    } else //if stops are not on the same route
                    {
                        //echo("not on the same route...");
                        $origin;
                        $destination;
                        $origin_route = $origin_route[0];
                        $destination_route = $destination_route[0];

                        $intersection = route_intersection($origin_route, $destination_route);

                        if ($intersection) //intersection exists
                        {
                   /* echo(" intersection is there1");
                    echo("<br>origin stop: ".$origin);
                    echo("<br>destination stop: ".$destination);
                    echo("<br>origin route: ".$origin_route);
                    echo("<br>destination route: ".$destination_route);
                    echo("<br>intersection: ");
                    print_r(reset($intersection));*/
                            $intersection_stop_id = reset($intersection); //get the first intersection point but need to include distance filters
                            $advice1 = advice($origin, $intersection_stop_id, $origin_route);
                            $advice2 = advice($intersection_stop_id, $destination, $destination_route);
                            //echo($advice1 . " " . $advice2);
                            $final_route_advice=$advice1 . " " . $advice2;
                            $status=-1;
                        } else //no intersection exists
                        {
                            //echo("<br>no intersection exists");

                            $origin;
                            $destination;


                            $origin_route = $origin_route;
                            $destination_route = $destination_route;
                            $origin_route_town_terminal = get_route_town_terminus($origin_route);
                            $destination_route_town_terminal = get_route_town_terminus($destination_route);



                            /*echo("<br>origin stop: ".$origin);
                             echo("<br>destination stop: ".$destination);
                             echo("<br>origin route: ".$origin_route);
                             echo("<br>destination route: ".$destination_route);
                             echo("<br>origin route terminal : ".$origin_route_town_terminal);
                             echo("<br>destination route terminal : ".$destination_route_town_terminal."<br>");*/


                            if ($origin_route_town_terminal && $destination_route_town_terminal) //successfully retrieved terminus for origin and destination
                            {
                                //correction needed to add functionality of third route inclusion here....first check if origin route terminal is in cbd
                                $advice1 = advice($origin, $origin_route_town_terminal, $origin_route);
                                $advice2 = advice($destination_route_town_terminal, $destination, $destination_route);
                               // echo($advice1 . " " . $advice2);
                                $final_route_advice=$advice1 . " " . $advice2;
                                $status=1;
                            } else //unsuccessfully retrieved terminus for origin and destination
                            {

                                $final_route_advice=null;
                                $status=-1;
                                //echo("error in retrieving route terminus");
                            }

                        }

                    }
                } else {
                    //return no result
                    //echo("There was an error: with the input ensure the destination or the origin is correct");
                    $final_route_advice=null;
                    $status=-1;
                }
            } else //destination and origin are not set
            {

                $final_route_advice=null;
                $status=-1;
            }
        }
        else//if the parameters passed contain errors
        {

            $final_route_advice=null;
            $status=-1;
        }

        //echo("<br>ufufufufufufu</br>");


        $this->load->model('stop_model');
        $origin_coordinates=array(
            'lat'=>$origin_lat,
            'lon'=>$origin_lon
        );
        $origin_stop_coordinates=$this->stop_model->get_stop_coordinates($origin);
        //print_r($origin_stop_coordinates);
        $origin_route_town_terminal_coordinates=$this->stop_model->get_stop_coordinates($origin_route_town_terminal);
        $destination_route_town_terminal_coordinates=$this->stop_model->get_stop_coordinates($destination_route_town_terminal);
        $destination_stop_coordinates=$this->stop_model->get_stop_coordinates($destination);
        $destination_coordinates=array(
            'lat'=>$destination_lat,
            'lon'=>$destination_lon
        );
        $intersection_coordinates=$this->stop_model->get_stop_coordinates($intersection_stop_id);

        $final_array=array(
            'status'=>$status,
            'advice'=>$final_route_advice,
            'origin'=>$origin_coordinates,
            'origin_stop'=>$origin_stop_coordinates,
            'origin_terminal'=>$origin_route_town_terminal_coordinates,
            'destination_terminal'=>$destination_route_town_terminal_coordinates,
            'destination_stop'=>$destination_stop_coordinates,
            'destination'=>$destination_coordinates,
            'intersection'=>$intersection_coordinates
        );

       // print_r($final_array);
        header('Content-Type: application/json');
        echo(json_encode($final_array,JSON_PRETTY_PRINT));


    }


}
