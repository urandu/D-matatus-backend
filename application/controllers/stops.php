<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stops extends CI_Controller {


	public function index()
	{
		$this->load->view('welcome_message');
	}

    public function get_trip($origin_lat,$origin_lon,$destination_lat,$destination_lon)
    {
        $origin;
        $destination;
        $this->load->model('route_model');
        $origin_route=$this->route_model->get_route($origin);
        //print_r($origin_route);

        $destination_route=$this->route_model->get_route($destination);

        //print_r($destination_route);
        if($destination_route && $origin_route)
        {
            $origin_and_destination_are_on_same_route=array_intersect($origin_route,$destination_route);
            //print_r($origin_and_destination_are_on_same_route);
            if($origin_and_destination_are_on_same_route)//if stops are on the same route
            {
                $origin_route=$origin_and_destination_are_on_same_route[0];
                $advice=advice($origin,$destination,$origin_route);
                if($advice)
                {
                    echo($advice);
                }
                else
                {
                    echo(-1);
                }
            }else//if stops are not on the same route
            {
                //echo("not on the same route...");
                $origin;
                $destination;
                $origin_route=$origin_route[0];
                $destination_route=$destination_route[0];

                $intersection=route_intersection($origin_route,$destination_route);

                if($intersection)//intersection exists
                {
//                    echo(" intersection is there1");
//                    echo("origin stop: ".$origin);
//                    echo("destination stop: ".$destination);
//                    echo("origin route: ".$origin_route);
//                    echo("destination route: ".$destination_route);
//                    echo("intersection: ");
//                    print_r(reset($intersection));
                    $intersection_stop_id=reset($intersection);//get the first intersection point but need to include distance filters
                    $advice1=advice($origin,$intersection_stop_id,$origin_route);
                    $advice2=advice($intersection_stop_id,$destination,$destination_route);
                    echo($advice1." ".$advice2);
                }
                else//no intersection exists
                {
                    //echo("no intersection exists");

                    $origin;
                    $destination;



                    $origin_route=$origin_route;
                    $destination_route=$destination_route;
                    $origin_route_town_terminal=get_route_town_terminus($origin_route);
                    $destination_route_town_terminal=get_route_town_terminus($destination_route);


                   /*echo("<br>origin stop: ".$origin);
                    echo("<br>destination stop: ".$destination);
                    echo("<br>origin route: ".$origin_route);
                    echo("<br>destination route: ".$destination_route);
                    echo("<br>origin route terminal : ".$origin_route_town_terminal);
                    echo("<br>destination route terminal : ".$destination_route_town_terminal."<br>");*/



                    if($origin_route_town_terminal && $destination_route_town_terminal)//successfully retrieved terminus for origin and destination
                    {
                        $advice1=advice($origin,$origin_route_town_terminal,$origin_route);
                        $advice2=advice($destination_route_town_terminal,$destination,$destination_route);
                        echo($advice1." ".$advice2);
                    }
                    else//unsuccessfully retrieved terminus for origin and destination
                    {

                        echo("error in retrieving route terminus");
                    }

                }

            }
        }
        else
        {
            //return no result
            echo("There was an error: with the input ensure the destination or the origin is correct");
        }
    }

}
