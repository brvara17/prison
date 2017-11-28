<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Hameedullah Pardess <hameedullah.pardess@gmail.com>
 *
 */

class Prisoner_model extends CI_Model {

    // table name
    private $tableName= 'prisoner';

    function __construct() {
        parent::__construct();
    }

    // get number of records in database
    function count_all(){
        return $this->db->count_all($this->tableName);
    }

    // get record by id
    function get_by_id($id, $column_list = '*'){
        $this->db->select($column_list);
        $this->db->from($this->tableName);
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->row();
    }

    // get record by id with joins
    function get_by_id_with_joins($id, $language){
        $this->db->select('`prisoner`.`id` AS `id`, `prisoner`.`license_number` AS `license_number`, `prisoner`.`eye_color_id` AS `eye_color_id`,`eye_color`.`status_' . $language . '` AS `eye_color`,`prisoner`.`hair_color_id` AS `hair_color_id`,`hair_color`.`status_' . $language . '` AS `hair_color`,`prisoner`.`present_province_id` AS `present_province_id`,`present_province`.`name_' . $language . '` AS `present_province`,`prisoner`.`present_district_id` AS `present_district_id`,`present_district`.`name_' . $language . '` AS `present_district`,`prisoner`.`permanent_province_id` AS `permanent_province_id`,`permanent_province`.`name_' . $language . '` AS `permanent_province`,`prisoner`.`permanent_district_id` AS `permanent_district_id`,`permanent_district`.`name_' . $language . '` AS `permanent_district`,`prisoner`.`name` AS `name`,`prisoner`.`middle_name` AS `middle_name`,`prisoner`.`last_name` AS `last_name`,`prisoner`.`age` AS `age`,`prisoner`.`criminal_history` AS `criminal_history`,`prisoner`.`num_of_children` AS `num_of_children`,`prisoner`.`profile_pic` AS `profile_pic`');
        $this->db->from($this->tableName);
        $this->db->join('eye_color AS eye_color', 'eye_color.id = ' . $this->tableName . '.eye_color_id', 'inner');
        $this->db->join('hair_color AS hair_color', 'hair_color.id = ' . $this->tableName . '.hair_color_id', 'inner');
        $this->db->join('province AS present_province', 'present_province.id = ' . $this->tableName . '.present_province_id', 'inner');
        $this->db->join('district AS present_district', 'present_district.id = ' . $this->tableName . '.present_district_id', 'inner');
        $this->db->join('province AS permanent_province', 'permanent_province.id = ' . $this->tableName . '.permanent_province_id', 'inner');
        $this->db->join('district AS permanent_district', 'permanent_district.id = ' . $this->tableName . '.permanent_district_id', 'inner');
        $this->db->where($this->tableName . '.id',$id);
        $query = $this->db->get();
        return $query->row();
    }

    // get record by id with joins
    function get_by_crime_id_with_joins($crime_id, $language){
        $this->db->select('`prisoner`.`id` AS `id`, `prisoner`.`license_number` AS `license_number`, `prisoner`.`eye_color_id` AS `eye_color_id`,`eye_color`.`status_' . $language . '` AS `eye_color`,`prisoner`.`hair_color_id` AS `hair_color_id`,`hair_color`.`status_' . $language . '` AS `hair_color`,`prisoner`.`present_province_id` AS `present_province_id`,`present_province`.`name_' . $language . '` AS `present_province`,`prisoner`.`present_district_id` AS `present_district_id`,`present_district`.`name_' . $language . '` AS `present_district`,`prisoner`.`permanent_province_id` AS `permanent_province_id`,`permanent_province`.`name_' . $language . '` AS `permanent_province`,`prisoner`.`permanent_district_id` AS `permanent_district_id`,`permanent_district`.`name_' . $language . '` AS `permanent_district`,`prisoner`.`name` AS `name`,`prisoner`.`middle_name` AS `middle_name`,`prisoner`.`last_name` AS `last_name`,`prisoner`.`age` AS `age`,`prisoner`.`criminal_history` AS `criminal_history`,`prisoner`.`num_of_children` AS `num_of_children`,`prisoner`.`profile_pic` AS `profile_pic`');
        $this->db->from($this->tableName);
        $this->db->join('eye_color AS eye_color', 'eye_color.id = ' . $this->tableName . '.eye_color_id', 'inner');
        $this->db->join('hair_color AS hair_color', 'hair_color.id = ' . $this->tableName . '.hair_color_id', 'inner');
        $this->db->join('province AS present_province', 'present_province.id = ' . $this->tableName . '.present_province_id', 'inner');
        $this->db->join('district AS present_district', 'present_district.id = ' . $this->tableName . '.present_district_id', 'inner');
        $this->db->join('province AS permanent_province', 'permanent_province.id = ' . $this->tableName . '.permanent_province_id', 'inner');
        $this->db->join('district AS permanent_district', 'permanent_district.id = ' . $this->tableName . '.permanent_district_id', 'inner');
        
        $this->db->join('crime_prisoner AS crime_prisoner', 'crime_prisoner.prisoner_id = ' . $this->tableName . '.id', 'inner');
        $this->db->where('crime_prisoner.crime_id',$crime_id);
        $query = $this->db->get();
        return $query->row();
    }

    // get all records
    function get_all($column_list = '*'){
        $this->db->select($column_list);
        $this->db->from($this->tableName);
        $query = $this->db->get();
        return $query->result();
    }

    // add new record
    function create($record){
        $this->db->insert($this->tableName, $record);
        return $this->db->insert_id();
    }

    // update the record by id
    function update_by_id($id, $record){
        $this->db->where('id', $id);
        $this->db->update($this->tableName, $record);
    }

    public function update($where, $data)
    {
        $this->db->update($this->tableName, $data, $where);
        return $this->db->affected_rows();
    }

    // delete record by id
    function delete_by_id($id){
        $this->db->where('id', $id);
        $this->db->delete($this->tableName);
    }
}