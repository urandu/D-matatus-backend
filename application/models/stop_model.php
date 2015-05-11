<?php
/**
 * Created by IntelliJ IDEA.
 * User: urandu
 * Date: 5/5/15
 * Time: 12:14 AM
 */
class Stop_model extends CI_Model
{

    public function stop_details($stop_id)
    {
        $this->db->where('stop_id',$stop_id);
        $this->db->limit(1);
        $result=$this->db->get('stops');
        if($result->num_rows() > 0)
        {

            //echo('stop details returned result');
            return $result->result()[0];
        }
        else
        {
            //echo('stop details did not return result');
            return false;
        }
    }


}