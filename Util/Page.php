<?php

namespace Util;

class Page
{
    public static function show($total, $page_size)
    {
        $page = \S\Http\Request::get('p', 1);    //当前页码
        $total_page = ceil($total / $page_size);        //总页数
        $show_page = $total_page < 5 ? $total_page : 5;       //分页显示页数
        $show_page_half = floor($show_page / 2);        //当前页面左右页数

        if ($page - $show_page_half < 1) {
            $start = 1;
            $end = $show_page;
        } else {
            $start = $page - $show_page_half;
            $end = $page + $show_page_half;
            if ($end > $total_page) {
                $start = $total_page - $show_page + 1;
                $end = $total_page;
            }
        }


        $uri = explode('?', \S\Http\Request::server('REQUEST_URI'));
        $uri_path = $uri[0] ?? '/';
        $uri_query = $uri[1] ?? '';
        parse_str($uri_query, $uri_arr);

        $pages = '<nav aria-label="Page navigation"><ul class="pagination">';

        //首页
        $uri_arr['p'] = 1;
        $new_uri = $uri_path . '?' . \GuzzleHttp\Psr7\build_query($uri_arr);
        $active = 1 == $page ? ' class="active" ' : '';
        $pages .= '<li ' . $active . '><a href="' . $new_uri . '" aria-label="Previous"><span aria-hidden="true">&laquo;首页</span></a></li>';

        for ($i = $start; $i <= $end; $i++) {
            $uri_arr['p'] = $i;
            $new_uri = $uri_path . '?' . \GuzzleHttp\Psr7\build_query($uri_arr);
            $active = $i == $page ? ' class="active" ' : '';
            $pages .= '<li' . $active . '><a href="' . $new_uri . '">' . $i . '</a></li>';

        }

        //尾页
        $uri_arr['p'] = $total_page;
        $new_uri = $uri_path . '?' . \GuzzleHttp\Psr7\build_query($uri_arr);
        $active = $total_page == $page ? ' class="active" ' : '';
        $pages .= '<li ' . $active . '><a href="' . $new_uri . '" aria-label="Next"><span aria-hidden="true">尾页&raquo;</span></a></li>';

        $pages .= '</ul></nav>';
        return $pages;
    }
}