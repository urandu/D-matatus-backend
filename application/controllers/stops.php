<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stops extends CI_Controller {


	public function index()
	{
		$this->load->view('welcome_message');
	}

    public function get_trip($origin,$destination)
    {
        $this->load->model('route_model');
        $origin_route=$this->route_model->get_route($origin);
        echo($origin_route."<br>");

        $destination_route=$this->route_model->get_route($destination);
        echo($destination_route);
        if($destination_route && $origin_route)
        {
            if($destination_route==$origin_route)//if stops are on the same route
            {
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

            }
        }
        else
        {
            //return no result
        }
    }

}
