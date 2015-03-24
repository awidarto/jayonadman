<?php

class StatsController extends BaseReportController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = strtolower(str_replace('Controller', '', get_class()));

        //$this->crumb = new Breadcrumb();
        //$this->crumb->append('Home','left',true);
        //$this->crumb->append(strtolower($this->controller_name));

        $this->model = new Approval();
        //$this->model = DB::collection('documents');

    }


    public function getIndex()
    {

        Breadcrumbs::addCrumb('Statistics',URL::to($this->controller_name));

        $this->report_action = $this->controller_name;

        $this->additional_filter = View::make('stats.addfilter')
            ->with('report_action', $this->report_action)
            ->render();

            $daterange = Input::get('date_filter');

            if($daterange == '' || is_null($daterange)){
                $daterange = date('01-m-Y',time()).' - '.date('t-m-Y',time());
            }

            $daterange = explode(' - ', $daterange);
            $start = Carbon::parse($daterange[0]);
            $end = Carbon::parse($daterange[1]);
            $end->addDay();

            $timerange = array();

            do{

                $endday = clone($start);
                $endday->addHours(23)->addMinutes(59)->addSeconds(59);
                //print $start->toDateTimeString().' - '.$endday->toDateTimeString()."\r\n";

                $timerange[] = array('start'=>$start->toDateTimeString(), 'end'=>$endday->toDateTimeString());

                $start->addDay();
            }while($start != $end);

            $views = array();
            $clicks = array();
            $labels = array();
            foreach ($timerange as $t) {
                $from = new MongoDate( strtotime( $t['start'] ) );
                $to = new MongoDate( strtotime( $t['end'] ) );

                $clicks[] = Clicklog::whereBetween('clickedAt', array($from, $to) )->count();
                $views[] = Viewlog::whereBetween('viewedAt', array($from, $to) )->count();

                $labels[] = date('d-m-Y', strtotime( $t['start'] ) );
            }




            //print_r($clicks);

                $clickData = array(
                    'label'=>'Clicks',
                    'fillColor'=>'rgba(123,109,112,0.5)',
                    'strokeColor'=>'rgba(123,109,112,1)',
                    'pointColor'=>'rgba(123,109,112,1)',
                    'pointStrokeColor'=>'#fff',
                    'pointHighlightFill'=>'#fff',
                    'pointHighlightStroke'=>'rgba(220,220,220,1)',
                    'data'=>$clicks
                );

                $viewData = array(
                    'label'=>'Views',
                    'fillColor'=>'rgba(234,219,196,0.5)',
                    'strokeColor'=>'rgba(234,219,196,1)',
                    'pointColor'=>'rgba(234,219,196,1)',
                    'pointStrokeColor'=>'#fff',
                    'pointHighlightFill'=>'#fff',
                    'pointHighlightStroke'=>'rgba(220,220,220,1)',
                    'data'=>$views
                );


                $this->data = array(
                    'series01'=>$viewData,
                    'series02'=>$clickData,
                    'labels'=>$labels
                    );

        $this->report_view = 'stats.report';
        $this->title = 'Global Statistics';
        return parent::getIndex();

    }


    public function getMerchant($mid = null)
    {

        Breadcrumbs::addCrumb('Statistics',URL::to($this->controller_name));

        $this->report_action = $this->controller_name;

        $this->additional_filter = View::make('stats.addfilter')
            ->with('report_action', $this->report_action)
            ->render();

            $daterange = Input::get('date_filter');

            if($daterange == '' || is_null($daterange)){
                $daterange = date('01-m-Y',time()).' - '.date('t-m-Y',time());
            }

            $daterange = explode(' - ', $daterange);
            $start = Carbon::parse($daterange[0]);
            $end = Carbon::parse($daterange[1]);
            $end->addDay();

            $timerange = array();

            do{

                $endday = clone($start);
                $endday->addHours(23)->addMinutes(59)->addSeconds(59);
                //print $start->toDateTimeString().' - '.$endday->toDateTimeString()."\r\n";

                $timerange[] = array('start'=>$start->toDateTimeString(), 'end'=>$endday->toDateTimeString());

                $start->addDay();
            }while($start != $end);

            $views = array();
            $clicks = array();
            $labels = array();
            foreach ($timerange as $t) {
                $from = new MongoDate( strtotime( $t['start'] ) );
                $to = new MongoDate( strtotime( $t['end'] ) );

                if(is_null($mid)){
                    $clicks[] = Clicklog::whereBetween('clickedAt', array($from, $to) )->count();
                    $views[] = Viewlog::whereBetween('viewedAt', array($from, $to) )->count();
                }else{
                    $clicks[] = Clicklog::whereBetween('clickedAt', array($from, $to) )
                                    ->where('merchantId',$mid)
                                    ->count();
                    $views[] = Viewlog::whereBetween('viewedAt', array($from, $to) )
                                    ->where('merchantId',$mid)
                                    ->count();
                }

                $labels[] = date('d-m-Y', strtotime( $t['start'] ) );
            }




            //print_r($clicks);

                $clickData = array(
                    'label'=>'Clicks',
                    'fillColor'=>'rgba(123,109,112,0.5)',
                    'strokeColor'=>'rgba(123,109,112,1)',
                    'pointColor'=>'rgba(123,109,112,1)',
                    'pointStrokeColor'=>'#fff',
                    'pointHighlightFill'=>'#fff',
                    'pointHighlightStroke'=>'rgba(220,220,220,1)',
                    'data'=>$clicks
                );

                $viewData = array(
                    'label'=>'Views',
                    'fillColor'=>'rgba(234,219,196,0.5)',
                    'strokeColor'=>'rgba(234,219,196,1)',
                    'pointColor'=>'rgba(234,219,196,1)',
                    'pointStrokeColor'=>'#fff',
                    'pointHighlightFill'=>'#fff',
                    'pointHighlightStroke'=>'rgba(220,220,220,1)',
                    'data'=>$views
                );


                $this->data = array(
                    'series01'=>$viewData,
                    'series02'=>$clickData,
                    'labels'=>$labels
                    );

        $this->report_view = 'stats.report';
        $this->title = 'Global Statistics';
        return parent::getIndex();

    }

    public function makeActions($data)
    {
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="fa fa-trash"></i>Delete</span>';
        $edit = '<a href="'.URL::to('document/edit/'.$data['_id']).'"><i class="fa fa-edit"></i>Update</a>';

        $actions = $edit.'<br />'.$delete;
        return $actions;
    }

    public function splitTag($data){
        $tags = explode(',',$data['docTag']);
        if(is_array($tags) && count($tags) > 0 && $data['docTag'] != ''){
            $ts = array();
            foreach($tags as $t){
                $ts[] = '<span class="tag">'.$t.'</span>';
            }

            return implode('', $ts);
        }else{
            return $data['docTag'];
        }
    }

    public function splitShare($data){
        $tags = explode(',',$data['docShare']);
        if(is_array($tags) && count($tags) > 0 && $data['docShare'] != ''){
            $ts = array();
            foreach($tags as $t){
                $ts[] = '<span class="tag">'.$t.'</span>';
            }

            return implode('', $ts);
        }else{
            return $data['docShare'];
        }
    }

    public function namePic($data)
    {
        $name = HTML::link('products/view/'.$data['_id'],$data['productName']);
        if(isset($data['thumbnail_url']) && count($data['thumbnail_url'])){
            $display = HTML::image($data['thumbnail_url'][0].'?'.time(), $data['filename'][0], array('id' => $data['_id']));
            return $display.'<br />'.$name;
        }else{
            return $name;
        }
    }

    public function pics($data)
    {
        $name = HTML::link('products/view/'.$data['_id'],$data['productName']);
        if(isset($data['thumbnail_url']) && count($data['thumbnail_url'])){
            $display = HTML::image($data['thumbnail_url'][0].'?'.time(), $data['filename'][0], array('style'=>'min-width:100px;','id' => $data['_id']));
            return $display.'<br /><span class="img-more" id="'.$data['_id'].'">more images</span>';
        }else{
            return $name;
        }
    }

    public function getViewpics($id)
    {

    }


}
