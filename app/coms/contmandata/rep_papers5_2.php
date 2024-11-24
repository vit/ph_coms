<?
//$PAGEID = 'papers';

$decision = $_REQUEST[decision];
$decisiontitle = $FINALPAPERDECISIONS[$decision];


$decisioncond2 = "";
if(!$decision>0) {
  $decision=0;
  $decisioncond2 = "OR p.finaldecision IS NULL";
}


  $n = 0;
  $result = pg_query("
SELECT
  COUNT(p.*)
FROM 
  paper AS p
  WHERE p.context=$CURRENTCONT AND (p.finaldecision=$decision $decisioncond2)
");
  if( $row = pg_fetch_array($result) ) {
    $n = $row[0];
  }


  $PAGEBODY .= <<<PAGEBODY
<br>
<center><span class=subtitle>Context: $CURRENTCONTTITLE</span></center>
<br>
<center><span class=subtitle>Report: Papers with final decision <i>"$decisiontitle"</i> (short)<br>Found $n papers</span></center>
<p>

PAGEBODY;









$cellclass1 = "cell1";
$cellclass2 = "cell2";
$cellclass = $cellclass1;

  $result = pg_query("
SELECT
  p.*,
--  concatpaperauthorswopin(p.context, p.papnum) AS authors,
  concatpaperkeywords(p.context, p.papnum, 3) AS keywords1,
  concatpaperkeywords(p.context, p.papnum, 2) AS keywords2,
  getfullnamewopin(p.registrator) AS registratorname
FROM 
  paper AS p
  WHERE p.context=$CURRENTCONT AND (p.finaldecision=$decision $decisioncond2)
ORDER BY p.papnum
");

  while( $row = pg_fetch_array($result) ) {
    $papid = $row['papnum'];
    $ctitle1 = htmlspecialchars($row[title]);
    $cauthors1 = $row['authors'];
    $ckeywords1 = $row['keywords1'];
    $ckeywords2 = $row['keywords2'];

    $subject1 = (int)$row['subject'];
    $subject1_str = $SUBJECTS[$subject1];

    $cabstract1 = nl2br( htmlspecialchars($row[abstract]) );
    $registratorname = $row['registratorname'];
    $editorname = $row['editorname'];

    $ed_subject = (int)$row['ed_subject'];
    $ed_subject_str = $SUBJECTS[$ed_subject];

    $ed_score = (int)$row['ed_score'];
    $ed_score_str = $SCORES[$ed_score];

    $ed_decision = (int)$row['ed_recommendation'];
    $ed_decision_str = $EDITORPAPERDECISIONS[$ed_decision];


    $cipccomments = nl2br( htmlspecialchars($row[ed_ipccomments]) );
    $cauthcomments = nl2br( htmlspecialchars($row[ed_authcomments]) );

    $r_subject = (int)$row['r_subject'];
    $r_subject_str = $SUBJECTS[$r_subject];

    $r_score = (int)$row['r_score'];
    $r_score_str = $SCORES[$r_score];

    $r_decision = (int)$row['r_recommendation'];
    $r_decision_str = $REVIEWERPAPERDECISIONS[$r_decision];

    $rcipccomments = nl2br( htmlspecialchars($row[r_ipccomments]) );
    $rcauthcomments = nl2br( htmlspecialchars($row[r_authcomments]) );


    $fin_subject = (int)$row['finalsubject'];
    $fin_subject_str = $SUBJECTS[$fin_subject];

    $fin_decision = (int)$row['finaldecision'];
    $fin_decision_str = $FINALPAPERDECISIONS[$fin_decision];


//    $filename = $papersdir."/c".$CURRENTCONT."p".$row[papnum];
//    if ( file_exists($filename) )
//      $filetext = "Yes";
//    else
//      $filetext = "No";



    $PAGEBODY .= <<<PAGEBODY
<b>ID:</b> $papid; <b>Title:</b> $ctitle1<br>
<b>Registrator:</b> $registratorname; <b>Authors (text):</b> $cauthors1
<p>

PAGEBODY;


    $cellclass1 = $cellclass2;
    $cellclass2 = $cellclass;
    $cellclass = $cellclass1;

  }





?>