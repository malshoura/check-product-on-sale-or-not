
$result1 = $dbh->prepare("SELECT wp.ID , wp.post_title , wpm.meta_value AS price , wpp.guid AS image ,wpmmm.meta_value AS reg_price
                          FROM wp_posts AS wp 
                          JOIN wp_term_relationships AS wtr 
                          ON wp.ID = wtr.object_id 
                          JOIN wp_postmeta AS wpm 
                          ON wp.ID = wpm.post_id 
                          JOIN wp_postmeta AS wpmm 
                          ON wp.ID = wpmm.post_id 
                          JOIN wp_posts AS wpp 
                          ON wpp.ID = wpmm.meta_value 
                          JOIN wp_postmeta AS wpmmm 
                          ON wp.ID = wpmmm.post_id 
                          WHERE wtr.term_taxonomy_id = $catid 
                          AND wp.post_status = 'publish' 
                          AND wpm.meta_key = '_price' 
                          AND wpmm.meta_key = '_thumbnail_id' 
                          AND wpmmm.meta_key = '_regular_price'   ");
$result1->execute();
             
    while($childarray = $result1->fetch(PDO::FETCH_ASSOC) )
        
    {
 $result2 = $dbh->query("SELECT p.ID, pm.* 
                         FROM wp_posts p 
                         JOIN wp_postmeta pm 
                         ON p.ID = pm.post_id 
                         WHERE p.post_type = 'product' 
                         AND p.ID = $prodid 
                         AND pm.meta_key IN('_regular_price','_sale_price')  ");
         $rowscheck = $result2->fetchAll();
        // echo count($rowscheck) ;
         if (count($rowscheck) == 1){
             $saleprice = "";
             
         }else{
              $saleprice = $childarray['price'];
         }

}
