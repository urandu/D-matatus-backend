<?php
/**
 * Created by IntelliJ IDEA.
 * User: urandu
 * Date: 5/5/15
 * Time: 12:14 AM
 */
class Stop_model extends CI_Model
{

    public function get_stop_coordinates($stop_id="ooo")
    {
        $this->db->where('stop_id',$stop_id);
        $this->db->limit(1);
        $result=$this->db->get('stops');
        //print_r( $result);
        if($result->num_rows() > 0)
        {

            //echo('stop  coordinates details returned result');
            //return $result->result()[0];
            $result_array=array(
                'name'=>$result->result()[0]->stop_name,
                'lat'=>$result->result()[0]->stop_lat,
                'lon'=>$result->result()[0]->stop_lon
            );
            return $result_array;
        }
        else
        {
            //echo('stop details did not return result');
            return false;
        }
    }



    public function get_stop_name($stop_id="ooo")
    {
        $this->db->where('stop_id',$stop_id);
        $this->db->limit(1);
        $result=$this->db->get('stops');
        //print_r( $result);
        if($result->num_rows() > 0)
        {

            //echo('stop  coordinates details returned result');
            //return $result->result()[0];
            return $result->result()[0]->stop_name;

        }
        else
        {
            //echo('stop details did not return result');
            return false;
        }
    }




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
    public function get_nearest_stop($stop_lat,$stop_lon)
    {
        $query="SELECT stop_name,stop_id,stop_lat, stop_lon, SQRT(
    POW(69.1 * (stop_lat - $stop_lat), 2) +
    POW(69.1 * ($stop_lon - stop_lon) * COS(stop_lat / 57.3), 2)) AS distance
FROM stops HAVING distance < 50
ORDER BY `distance`  ASC LIMIT 5";

        $result=$this->db->query($query);

        if($result->num_rows() > 0)
        {

            $result=$result->result();

                return $result[0]->stop_id;

        }
        else
        {
            return false;
        }



    }


}