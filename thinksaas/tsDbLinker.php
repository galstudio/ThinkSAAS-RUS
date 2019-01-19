<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );
/**
 * tsDbLinker
 */
class tsDbLinker
{
    private $model_obj = null;
    private $prepare_result = null;
    private $run_result = null;
    private $methods = array('find','findBy','findAll','run','create','delete','deleteByPk','update');
    public $enabled = TRUE;

    public function __input(& $obj, $args = null){
        $this->model_obj = $obj;
        return $this;
    }

    /**
     * tsDbLinker()->run($result)
     * @param result
     */
    public function run($result = FALSE){
        if( FALSE == $result )return FALSE;
        $this->run_result = $result;
        return $this->__call('run', null);
    }

    /**
     * tsDbLinker
     */
    public function __call($func_name, $func_args){
        if( in_array( $func_name, $this->methods ) && FALSE != $this->enabled ){
            if( 'delete' == $func_name || 'deleteByPk' == $func_name )$maprecords = $this->prepare_delete($func_name, $func_args);
            if( null != $this->run_result ){
                $run_result = $this->run_result;
            }elseif( !$run_result = call_user_func_array(array($this->model_obj, $func_name), $func_args) ){
                if( 'update' != $func_name )return FALSE;
            }
            if( null != $this->model_obj->linker && is_array($this->model_obj->linker) ){
                foreach( $this->model_obj->linker as $linkey => $thelinker ){
                    if( !isset($thelinker['map']) )$thelinker['map'] = $linkey;
                    if( FALSE == $thelinker['enabled'] )continue;
                    $thelinker['type'] = strtolower($thelinker['type']);
                    if( 'find' == $func_name || 'findBy' == $func_name ){
                        $run_result[$thelinker['map']] = $this->do_select( $thelinker, $run_result );
                    }elseif( 'findAll' == $func_name || 'run' == $func_name ){
                        foreach( $run_result as $single_key => $single_result )
                            $run_result[$single_key][$thelinker['map']] = $this->do_select( $thelinker, $single_result );
                    }elseif( 'create' == $func_name ){
                        $this->do_create( $thelinker, $run_result, $func_args );
                    }elseif( 'update' == $func_name ){
                        $this->do_update( $thelinker, $func_args );
                    }elseif( 'delete' == $func_name || 'deleteByPk' == $func_name ){
                        $this->do_delete( $thelinker, $maprecords );
                    }
                }
            }
            return $run_result;
        }elseif(in_array($func_name, $GLOBALS['G_SP']["auto_load_model"])){
            return aac($func_name)->__input($this, $func_args);
        }else{
            return call_user_func_array(array($this->model_obj, $func_name), $func_args);
        }
    }

    /**
     * @param func_name
     * @param func_args
     */
    private function prepare_delete($func_name, $func_args)
    {
        if('deleteByPk'==$func_name){
            return $this->model_obj->findAll(array($this->model_obj->pk=>$func_args[0]));
        }else{
            return $this->model_obj->findAll($func_args[0]);
        }
    }
    /**
     * @param thelinker
     * @param maprecords
     */
    private function do_delete( $thelinker, $maprecords ){
        if( FALSE == $maprecords )return FALSE;
        foreach( $maprecords as $singlerecord ){
            if(!empty($thelinker['condition'])){
                if( is_array($thelinker['condition']) ){
                    $fcondition = array($thelinker['fkey']=>$singlerecord[$thelinker['mapkey']]) + $thelinker['condition'];
                }else{
                    $fcondition = "{$thelinker['fkey']} = '{$singlerecord[$thelinker['mapkey']]}' AND {$thelinker['condition']}";
                }
            }else{
                $fcondition = array($thelinker['fkey']=>$singlerecord[$thelinker['mapkey']]);
            }
            $returns = aac($thelinker['fclass'])->delete($fcondition);
        }
        return $returns;
    }
    /**
     * @param thelinker
     * @param func_args
     */
    private function do_update( $thelinker, $func_args ){
        if( !is_array($func_args[1][$thelinker['map']]) )return FALSE;
        if( !$maprecords = $this->model_obj->findAll($func_args[0]))return FALSE;
        foreach( $maprecords as $singlerecord ){
            if(!empty($thelinker['condition'])){
                if( is_array($thelinker['condition']) ){
                    $fcondition = array($thelinker['fkey']=>$singlerecord[$thelinker['mapkey']]) + $thelinker['condition'];
                }else{
                    $fcondition = "{$thelinker['fkey']} = '{$singlerecord[$thelinker['mapkey']]}' AND {$thelinker['condition']}";
                }
            }else{
                $fcondition = array($thelinker['fkey']=>$singlerecord[$thelinker['mapkey']]);
            }
            $returns = aac($thelinker['fclass'])->update($fcondition, $func_args[1][$thelinker['map']]);
        }
        return $returns;
    }
    /**
     * @param thelinker
     * @param newid
     * @param func_args
     */
    private function do_create( $thelinker, $newid, $func_args ){
        if( !is_array($func_args[0][$thelinker['map']]) )return FALSE;
        if('hasone'==$thelinker['type']){
            $newrows = $func_args[0][$thelinker['map']];
            $newrows[$thelinker['fkey']] = $newid;
            return aac($thelinker['fclass'])->create($newrows);
        }elseif('hasmany'==$thelinker['type']){
            if(array_key_exists(0,$func_args[0][$thelinker['map']])){
                foreach($func_args[0][$thelinker['map']] as $singlerows){
                    $newrows = $singlerows;
                    $newrows[$thelinker['fkey']] = $newid;
                    $returns = aac($thelinker['fclass'])->create($newrows);
                }
                return $returns;
            }else{
                $newrows = $func_args[0][$thelinker['map']];
                $newrows[$thelinker['fkey']] = $newid;
                return aac($thelinker['fclass'])->create($newrows);
            }
        }
    }
    /**
     * @param thelinker
     * @param run_result
     */
    private function do_select( $thelinker, $run_result ){
        if(empty($thelinker['mapkey']))$thelinker['mapkey'] = $this->model_obj->pk;
        if( 'manytomany' == $thelinker['type'] ){
            $do_func = 'findAll';
            $midcondition = array($thelinker['mapkey']=>$run_result[$thelinker['mapkey']]);
            if( !$midresult = aac($thelinker['midclass'])->findAll($midcondition,null,$thelinker['fkey']) )return FALSE;
            $tmpkeys = array();foreach( $midresult as $val )$tmpkeys[] = "'".$val[$thelinker['fkey']]."'";
            if(!empty($thelinker['condition'])){
                if( is_array($thelinker['condition']) ){
                    $fcondition = "{$thelinker['fkey']} in (".join(',',$tmpkeys).")";
                    foreach( $thelinker['condition'] as $tmpkey => $tmpvalue )$fcondition .= " AND {$tmpkey} = '{$tmpvalue}'";
                }else{
                    $fcondition = "{$thelinker['fkey']} in (".join(',',$tmpkeys).") AND {$thelinker['condition']}";
                }
            }else{
                $fcondition = "{$thelinker['fkey']} in (".join(',',$tmpkeys).")";
            }
        }else{
            $do_func = ( 'hasone' == $thelinker['type'] ) ? 'find' : 'findAll';
            if(!empty($thelinker['condition'])){
                if( is_array($thelinker['condition']) ){
                    $fcondition = array($thelinker['fkey']=>$run_result[$thelinker['mapkey']]) + $thelinker['condition'];
                }else{
                    $fcondition = "{$thelinker['fkey']} = '{$run_result[$thelinker['mapkey']]}' AND {$thelinker['condition']}";
                }
            }else{
                $fcondition = array($thelinker['fkey']=>$run_result[$thelinker['mapkey']]);
            }
        }
        if(TRUE == $thelinker['countonly'])$do_func = "findCount";
        return aac($thelinker['fclass'])->$do_func($fcondition, $thelinker['sort'], $thelinker['field'], $thelinker['limit'] );
    }
}
