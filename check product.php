    <?php
        require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
        include ('config.php');
        $catid = 74;
       
             $result1 = $dbh->prepare("SELECT wp.ID , wp.post_title , wpp.guid AS image FROM wp_posts AS wp JOIN wp_term_relationships AS wtr ON wp.ID = wtr.object_id JOIN wp_postmeta AS wpmm ON wp.ID = wpmm.post_id JOIN wp_posts AS wpp ON wpp.ID = wpmm.meta_value WHERE wtr.term_taxonomy_id = $catid AND wp.post_status = 'publish' AND wpmm.meta_key = '_thumbnail_id' ORDER BY wp.post_date DESC   ");
              $result1->execute();
             
            
        $productcat = array(
            "cat_id" => 74
            );
           
         while($childarray = $result1->fetch(PDO::FETCH_ASSOC) )
        
    {
        $prodid = $childarray[ID] ;
        
         $result2 = $dbh->query("SELECT p.ID, pm.* FROM wp_posts p JOIN wp_postmeta pm ON p.ID = pm.post_id WHERE p.post_type = 'product' AND p.ID = $prodid AND pm.meta_key IN('_regular_price','_sale_price')  ");
         $rowscheck = $result2->fetchAll();
        
     
        
        
    $end_date_sale = get_post_meta($prodid,'_sale_price_dates_to',true);
    $start_date_sale = get_post_meta($prodid,'_sale_price_dates_from',true);
     $product_reg_price = get_post_meta($prodid,'_regular_price',true);
      $product_sale_price = get_post_meta($prodid,'_price',true);
         
         
         if (! empty( $start_date_sale || $end_date_sale ) ) {
             
                $date = new DateTime();
        $now =  $date->format('U = Y-m-d H:i:s');
 
             
         $startdate = new DateTime();
         $startdate->setTimestamp($start_date_sale);
       $start_date_salef =  $startdate->format('U = Y-m-d H:i:s');
     



$enddate = new DateTime();
         $enddate->setTimestamp($end_date_sale);
       $end_date_salef =  $enddate->format('U = Y-m-d H:i:s');
         
         


         // to check if now in between now and start and end date 
         if ($start_date_salef > $now || $now > $end_date_salef || ($now < $end_date_salef && $start_date_salef > $now ) ){
            // echo "sale start" ;
              $saleprice = "";
           
             
         }elseif (($start_date_salef < $now && $now < $end_date_salef) || ($start_date_salef < $now) || ($now < $end_date_salef) ){
             //echo "not sale yet";
             $saleprice = $product_sale_price;
         
         }else{
           $saleprice = "";  
         }
         
         
         }else{
       
        // echo count($rowscheck) ;
         if (count($rowscheck) == 1){
             $saleprice = "";
             
         }else{
              $saleprice = $product_sale_price;
         }
        
         }
        $product_reg_price = get_post_meta($prodid,'_regular_price',true);
        $productcat["products"][] = array(
            "product_id" => (int)$childarray[ID],
            "product_title" => $childarray['post_title'],
            "sale_price" => $saleprice,
            "product_price" => $product_reg_price,
            "product_image" => $childarray['image']

            );
           
    }
    $jsonArray[] = $productcat;
   

    
  
   echo json_encode($jsonArray);
    
  ?>
    
