<?php

namespace controllers;

class index extends controller
{

    public function __construct()
    {
        parent::__construct();
        $this->cms_page('index');
    }

    function get_data()
    {
        $query = "SELECT a.*, f.meta_value as slider_image FROM m_sliders a LEFT OUTER JOIN files f ON a.slider_image=f.id WHERE a.publish='Y' ORDER BY a.display_no";
        $this->list['sliders'] = $this->db->selectall($query);

        $query = "SELECT a.id, a.ad_title, a.ad_desc, a.ad_price, a.promoted, a.add_date, CONCAT_WS(' &raquo; ', c.category_name, s.category_name) as ad_cat, CONCAT(a.ad_city, ', ', a.ad_state,', Nigeria') as location, f.meta_value as ad_image FROM m_ads a "
            . "LEFT OUTER JOIN m_ads_cat c ON a.ad_category_id=c.id "
            . "LEFT OUTER JOIN m_ads_cat s ON a.ad_category_id=c.id AND a.ad_sub_category_id=s.id "
            . "LEFT OUTER JOIN files f ON a.ad_image=f.id "
            . "WHERE a.publish='Y' AND a.ad_sold='N' AND a.deleted='N'" . ($this->session('user', 'reports') ? " AND a.id NOT IN (" . implode(',', $this->session('user', 'reports')) . ")" : '') . " ORDER BY id DESC LIMIT 52";
        $this->list['ads'] = $this->db->selectall($query);

        $query = "SELECT b.id, b.blog_title, b.blog_desc, b.blog_date, b.page_url, f.meta_value as blog_image FROM m_blogs b "
            . "LEFT OUTER JOIN files f ON b.blog_image=f.id "
            . "WHERE b.publish='Y' ORDER BY blog_date DESC LIMIT 4";
        $this->list['blogs'] = $this->db->selectall($query);
    }


}
