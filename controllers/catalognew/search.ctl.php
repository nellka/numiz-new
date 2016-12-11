<?php
 if ($search) {

        $search = str_replace("'","",$search);
        $SearchTemp = explode(' ',trim(urldecode($search)));
        unset($SearchTempDigit);
        unset($SearchTempStr);
        unset($SearchTempMatch);
        unset($WhereThemesearch);
        unset($WhereConditionsearch);
        $tigger=0;
        if (sizeof($SearchTemp)>0) {
            for ($i=0;$i<sizeof($SearchTemp);$i++) {

                if (!trim($SearchTemp[$i]))
                    continue;

                if (intval($SearchTemp[$i]) ) {

                    if ( ($SearchTemp[$i-1] && intval($SearchTemp[$i-1])) || !$SearchTemp[$i+1] || intval($SearchTemp[$i+1])) {

                        $SearchTempMatch[] = $SearchTemp[$i];
                        $tigger=0;
                    }
                    else
                        $tigger=1;

                    $SearchTempDigit[] = $SearchTemp[$i];
                }
                else {

                    $SearchTemp[$i] = str_replace("+"," ",$SearchTemp[$i]);
                    $SearchTempStr[] = (strlen(trim($SearchTemp[$i]))>4?substr(trim($SearchTemp[$i]),0,strlen(trim($SearchTemp[$i]))-1):trim($SearchTemp[$i]));
                    if ($i>0)
                        $SearchTempMatch[] = ">".(strlen(trim($SearchTemp[$i]))>4?substr(trim($SearchTemp[$i]),0,strlen(trim($SearchTemp[$i]))-1)."*":trim($SearchTemp[$i]));
                    else
                        $SearchTempMatch[] = (strlen(trim($SearchTemp[$i]))>4?substr(trim($SearchTemp[$i]),0,strlen(trim($SearchTemp[$i]))-1)."*":trim($SearchTemp[$i]));

                    if ($tigger==1) {

                        $SearchTempMatch[] = '"'.$SearchTemp[$i-1]." ".trim($SearchTemp[$i]).'"';
                        $SearchTempMatch[] = $SearchTemp[$i-1];
                        $tigger=0;
                    }

                }

                foreach ($ThemeArray as $key=>$value) {
                    if (stristr($SearchTemp[$i],$value) ) {
                        $WhereThemesearch[] = "catalognew.`theme` & ".pow(2,$key).">0";
                    }
                }

                foreach ($ConditionArray as $key=>$value) {
                    if (stristr($SearchTemp[$i],$value) ) {
                        $WhereConditionsearch[] = $key;
                    }
                }

            }
        }

        $sql_temp = "select distinct `group`.`name`, `group`.* from `group`, `catalognew` 
					where catalognew.`agreement`>=0 and catalognew.`group`=`group`.`group`
					and (".(sizeof($SearchTempStr)?"`group`.`name` like '".implode("%' or `group`.`name` like '",$SearchTempStr)."%')":"").";";
        //echo $sql_temp."<br>";
        $result_temp = mysql_query($sql_temp);
        unset($WhereCountry);
        while ($rows_temp = mysql_fetch_array($result_temp)) {

            $WhereCountry[] = $rows_temp['group'];
            if ($rows_temp['groupparent']==0) {

                $sql_tmp = "select distinct `group`.`group` from `group`, catalognew where catalognew.`agreement`>=0 and catalognew.`group`=`group`.`group` and `group`.groupparent='".$rows_temp['group']."';";
                $result_tmp = mysql_query($sql_tmp);
                //			echo $sql_tmp."<br>";
                while ($rows_tmp = mysql_fetch_array($result_tmp)) {
                    $WhereCountry[] = $rows_tmp['group'];
                }
            }
        }

        $CounterSQL = '';
        $CounterSQL = (sizeof($SearchTempMatch)?" MATCH(catalognew.`name`,catalognew.averslegend,catalognew.mint,catalognew.reverselegend,catalognew.details,catalognew.herd,metal.`name`) AGAINST('".implode(" ",$SearchTempMatch)." ' IN BOOLEAN MODE) as coefficientcoins, MATCH(`group`.`name`) AGAINST('".implode(" ",$SearchTempMatch)." ' IN BOOLEAN MODE) as coefficientgroup":"");

        if (sizeof($WhereThemesearch) || sizeof($SearchTempDigit) || sizeof($WhereConditionsearch)) {

            $CounterSQL .= ", if(".
                (sizeof($WhereThemesearch)?
                    implode(" or ",$WhereThemesearch).", ".
                    (sizeof($SearchTempDigit)?
                        "if( catalognew.yearstart in ('".implode("','",$SearchTempDigit)."') and catalognew.yearstart<>0,".
                        (sizeof($WhereConditionsearch)?
                            "if( catalognew.condition in ('".implode("','",$WhereConditionsearch)."') and catalognew.condition<>0,'4','3'),'2')"
                            :
                            "'3','2')"
                        )
                        :
                        (sizeof($WhereConditionsearch)?
                            "if( catalognew.condition in ('".implode("','",$WhereConditionsearch)."') and catalognew.condition<>0,'3','2')"
                            :
                            "'2'"
                        )
                    ).", ".
                    (sizeof($SearchTempDigit)?
                        "if( catalognew.yearstart in ('".implode("','",$SearchTempDigit)."') and catalognew.yearstart<>0,".
                        (sizeof($WhereConditionsearch)?
                            "if( catalognew.condition in ('".implode("','",$WhereConditionsearch)."') and catalognew.condition<>0,'2','1.5')"
                            :
                            "'1.5','0')"
                        )
                        :
                        (sizeof($WhereConditionsearch)?
                            "if( catalognew.condition in ('".implode("','",$WhereConditionsearch)."') and catalognew.condition<>0,'1.5','0')"
                            :
                            "'0'"
                        )
                    )
                    :
                    (sizeof($SearchTempDigit)?
                        " catalognew.yearstart in ('".implode("','",$SearchTempDigit)."') and catalognew.yearstart<>0,".
                        (sizeof($WhereConditionsearch)?
                            "if( catalognew.condition in ('".implode("','",$WhereConditionsearch)."') and catalognew.condition<>0,'2','1.5')"
                            :
                            "'1.5','0'"
                        )
                        :
                        (sizeof($WhereConditionsearch)?
                            " catalognew.condition in ('".implode("','",$WhereConditionsearch)."') and catalognew.condition<>0,'1.5','0'"
                            :
                            "0,0,0"
                        )
                    )
                ).") as counterthemeyear";
        }

        $where .= " and ( ".(sizeof($SearchTempStr)?"(catalognew.reverselegend like '%".implode("%' or catalognew.reverselegend like '%",$SearchTempStr)."%' and catalognew.reverselegend<>'')":"")."
		".(sizeof($SearchTempDigit)?"or (catalognew.details like '".implode("' or catalognew.details like '",$SearchTempDigit)."' and catalognew.details<>'')":"")."
		".(sizeof($SearchTempStr)?"or (catalognew.details like '".implode("' or catalognew.details like '",$SearchTempStr)."' and catalognew.details<>'')":"")."
		".(sizeof($SearchTempStr)?"or catalognew.averslegend in ('".implode("','",$SearchTempStr)."' and catalognew.averslegend<>'')":"")."
		".(sizeof($SearchTempDigit)?"or (catalognew.yearstart in ('".implode("','",$SearchTempDigit)."') and catalognew.yearstart<>0)":"")."
		".(sizeof($SearchTempStr)?"or (catalognew.mint like '%".implode("%' or catalognew.mint like '%",$SearchTempStr)."%' and catalognew.mint<>'')":"")."
		".(sizeof($SearchTempStr)?"or (catalognew.herd like '%".implode("%' or catalognew.herd like '%",$SearchTempStr)."%' and catalognew.herd<>'')":"")."
		".(sizeof($SearchTempStr)?"or (catalognew.`name` like '%".implode("%' or catalognew.`name` like '%",$SearchTempStr)."%' and catalognew.`name`<>'')":"")." 
		".(sizeof($SearchTempStr)?"or (metal.`name` like '%".implode("%' or metal.`name` like '%",$SearchTempStr)."%'  and metal.`name`<>'')":"")." 
		".(sizeof($WhereThemesearch)?" or ".implode(" or ",$WhereThemesearch):"")."
		".(sizeof($WhereConditionsearch)?" or catalognew.`condition` in (".implode(",",$WhereConditionsearch).") and catalognew.`condition`<>0":"")." 
		".(sizeof($WhereCountry)>0?" or catalognew.`group` in (".implode(",",$WhereCountry).")":"").")";
    }
    
     if ($search and $submit)
        {

            //$amount = 1;
            //записываем запрос к поиску
            $sql_tmp = "select * from searchkeywords where keywords='".lowstring($search)."' and page='$script';";
            $result_tmp = mysql_query($sql_tmp);
            $rows_tmp = mysql_fetch_array($result_tmp);
            if ($rows_tmp[0]==0)
            {
                $sql_tmp2 = "insert into searchkeywords values (0, '$maxcoefficient', '$sumcoefficient','".lowstring(strip_string($search))."', '$script', 1, '$amountsearch', '$timenow');";
            } else {
                $sql_tmp2 = "update searchkeywords set counter=counter+1, maxcoefficient='$maxcoefficient', sumcoefficient='$sumcoefficient', amount='$amountsearch' where keywords='".lowstring($search)."' and page='$script';";
            }
            $result_tmp2 = mysql_query($sql_tmp2);
            echo $sql_tmp2."=sql_tmp2<br>";
            //конец записи
        }