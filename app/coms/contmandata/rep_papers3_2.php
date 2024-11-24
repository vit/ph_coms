<?
//$PAGEID = 'papers';

$decision = $_REQUEST[decision];
$decisiontitle = $EDITORPAPERDECISIONS[$decision];


$decisioncond2 = "";
if(!$decision>0) {
  $decision=0;
  $decisioncond2 = "OR p.ed_recommendation IS NULL";
}


  $n = 0;
  $result = pg_query("
SELECT
  COUNT(p.*)
FROM 
  paper AS p
  WHERE p.context=$CURRENTCONT AND (p.ed_recommendation=$decision $decisioncond2)
");
  if( $row = pg_fetch_array($result) ) {
    $n = $row[0];
  }


  $PAGEBODY .= <<<PAGEBODY
<br>
<center><span class=subtitle>Context: $CURRENTCONTTITLE</span></center>
<br>
<center><span class=subtitle>Report: Papers with editor's decision <i>"$decisiontitle"</i> (short)<br>Found $n papers</span></center>
<p>

PAGEBODY;




$cellclass1 = "cell1";
$cellclass2 = "cell2";
$cellclass = $cellclass1;

$PAGEBODY .= <<<PAGEBODY
<center>

<table align=center wwidth=100% border=1>
<tr class=$cellclass2 aalign=left>
<th width=1 rowspan=6>ID</th>
<th>Title</th>
<!-- th>Section</th -->
<!-- th>Registrator</th -->
<th>Editor</th>
<th>File</th>
</tr>
<tr class=$cellclass2>
<th colspan=3>Authors (text)</th>
</tr>
<tr class=$cellclass2>
<th colspan=3>Keywords</th>
</tr>
<!-- tr class=cell3>
<th colspan=3>Abstract</th>
</tr -->
<tr class=cell8>
<th colspan=3>Final decision and section</th>
</tr>
<tr class=cell4>
<th colspan=3>Editor's recommendations: score, decision, section, comments</th>
</tr>
<tr class=cell6>
<th colspan=3>Reviewer's recommendations: score, decision, section</th>
</tr>

PAGEBODY;


  $result = pg_query("
SELECT
  p.*,
--  concatpaperauthors(p.context, p.papnum) AS authors,
  concatpaperkeywords(p.context, p.papnum, 3) AS keywords1,
  concatpaperkeywords(p.context, p.papnum, 2) AS keywords2,
  r.revpin AS revpin, r.score AS r_score, r.subject AS r_subject, r.ipccomments AS r_ipccomments, r.authcomments AS r_authcomments, r.recommendation AS r_recommendation,
  getfullnamewithpin(p.registrator) AS registratorname,
  getfullnamewithpin(e.userpin) AS editorname,
  getfullnamewithpin(r.revpin) AS revname
FROM 
  (paper AS p LEFT JOIN editor AS e ON p.editor=e.editorid)
    LEFT JOIN 
      review AS r ON p.papnum=r.papnum AND r.context=$CURRENTCONT
  WHERE p.context=$CURRENTCONT AND (p.ed_recommendation=$decision $decisioncond2)
ORDER BY p.ed_subject, p.ed_score, p.papnum, r.revpin
");
//ORDER BY p.papnum, r.revpin


$revcnt = 0;
$papid0 = 0;

  while( $row = pg_fetch_array($result) ) {
    $papid = $row['papnum'];
    $ctitle1 = htmlspecialchars($row[title]);
    $cauthors1 = $row['authors'];
    $ckeywords1 = $row['keywords1'];
    $ckeywords2 = $row['keywords2'];

    $subject1 = (int)$row['subject'];
    $subject1_str = $SUBJECTS[$subject1];

    $cabstract1 = nl2br( htmlspecialchars($row['abstract']) );
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


    $filename = $papersdir."/c".$CURRENTCONT."p".$row[papnum];
    if ( file_exists($filename) )
      $filetext = "Yes";
    else
      $filetext = "No";


if( $papid0 != $papid ) {

//  if($revcnt)
  if($papid0>0)
    $PAGEBODY .= <<<PAGEBODY
</td>
</tr>

PAGEBODY;

  $papid0 = $papid;



    $PAGEBODY .= <<<PAGEBODY
<tr class=$cellclass>
<td align=center rowspan=6>
  <a href="{$ROOT}paperinfo/c{$CURRENTCONT}p{$papid}r{$row[registrator]}.html" title="Paper information"
    onClick="window.open('{$ROOT}paperinfo/c{$CURRENTCONT}p{$papid}r{$row[registrator]}.html', '', 'location=no, menubar=no, toolbar=no, statusbar=no, scrollbars=yes, width=620, height=550, resizable=yes'); return false;"
  >[&nbsp;$papid&nbsp;]</a>
</td>
<td>$ctitle1</td>
<!-- td align=center>$subject1_str</td -->
<!-- td align=center>$registratorname</td -->
<td align=center>$editorname</td>
  <td align=center>
    $filetext
  </td>
</tr>

<tr class=$cellclass>
<td colspan=3><b>Authors (text):</b> $cauthors1</td>
</tr>
<tr class=$cellclass>
<td colspan=3><b>Main:</b> $ckeywords1; <b>Secondary:</b> $ckeywords2</td>
</tr>
<!-- tr class=cell3>
<td colspan=3>$cabstract1</td>
</tr -->

<tr class=cell8>
<td colspan=3>
<b>Final decision:</b> <span class=selection3>$fin_decision_str</span>;
<b>Final section:</b> <span class=selection3>$fin_subject_str</span>;
</td>
</tr>

<tr class=cell4>
<td colspan=3>
<b>Score:</b> <span class=selection1>$ed_score_str</span>;
<b>Recommended decision:</b> <span class=selection1>$ed_decision_str</span>;
<b>Recommended section:</b> <span class=selection1>$ed_subject_str</span>;
<br>
<b>Comments to IPC:</b> $cipccomments
<br>
<b>Comments to Authors:</b> $cauthcomments
</td>
</tr>

PAGEBODY;


//  if($row['revpin'])
    $PAGEBODY .= <<<PAGEBODY
<tr class=cell6>
<td colspan=3>

PAGEBODY;
  $revcnt=0;


    $cellclass1 = $cellclass2;
    $cellclass2 = $cellclass;
    $cellclass = $cellclass1;

}


    if($row['revpin']) {
      $revcnt++;
      $PAGEBODY .= <<<PAGEBODY
<b>&nbsp;&nbsp;Reviewer $revcnt - $row[revname]</b>
<br>
<b>Score:</b> <span class=selection2>$r_score_str</span>;
<b>Recommended decision:</b> <span class=selection2>$r_decision_str</span>;
<b>Recommended section:</b> <span class=selection2>$r_subject_str</span>;

<br>
<!-- b>Comments to IPC:</b> $rcipccomments
<br>
<b>Comments to Authors:</b> $rcauthcomments
<br -->

PAGEBODY;
    }



  }


if($papid0>0)
  $PAGEBODY .= <<<PAGEBODY
</td>
</tr>

PAGEBODY;


$PAGEBODY .= <<<PAGEBODY
</table>

PAGEBODY;





$PAGEBODY .= <<<PAGEBODY
<br>
</center>

PAGEBODY;











?>