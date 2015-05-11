<?php
/**
 * Created by IntelliJ IDEA.
 * User: urandu
 * Date: 5/5/15
 * Time: 12:14 AM
 */
class Route_model extends CI_Model
{

    public function get_route($stop_id)
    {
        $this->db->select('route_id');
        $this->db->where('stop_id',$stop_id);
       // $this->db->order_by("route_id", "asc");
        //$this->db->limit(1);

        $this->db->distinct();
        $result=$this->db->get('stop_times');
        if($result->num_rows() > 0)
        {

             $result=$result->result();
            foreach($result as $e)
            {
                $result_array[]=$e->route_id;
            }
            return $result_array;
        }
        else
        {
            return false;
        }

    }

    public function route_details($route_id)
    {
        $this->db->where('route_id',$route_id);
        $this->db->limit(1);
        $result=$this->db->get('routes');
        if($result->num_rows() > 0)
        {
           // echo('route details returned result');
            return $result->result()[0];

        }
        else
        {
            //echo('route details did not returned result');
            return false;
        }
    }



    public function get_route_stops($route_id)
    {
        $this->db->select('stop_id');
        $this->db->where('route_id',$route_id);
        // $this->db->order_by("route_id", "asc");
        //$this->db->limit(1);

        $this->db->distinct();
        $result=$this->db->get('stop_times');
        if($result->num_rows() > 0)
        {

            $result=$result->result();
            foreach($result as $e)
            {
                $result_array[]=$e->stop_id;
            }
            return $result_array;
        }
        else
        {
            return false;
        }
    }


    public function route_town_terminus($route_id)
    {
        //$this->db->select('stop_id');
        $this->db->where('route_id',$route_id);
        $this->db->where('direction',1);
        $this->db->order_by("stop_sequence", "asc");
        $this->db->limit(1);

        //$this->db->distinct();
        $result=$this->db->get('stop_times');
        if($result->num_rows() > 0)
        {



            $result=$result->result()[0]->stop_id;
            return $result;

        }
        else
        {
            return false;
        }
    }

}