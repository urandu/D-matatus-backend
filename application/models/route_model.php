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
        $this->db->where('stop_id');
        $this->db->limit(1);
        $result=$this->db->get('stops');
        if($result->num_rows() > 0)
        {

            return $result->result()[0]->route_id;
        }
        else
        {
            return false;
        }

    }


}