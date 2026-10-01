<?php
declare(strict_types=1);
$file=__DIR__.'/dailee-erd.svg';
$svg=['<svg xmlns="http://www.w3.org/2000/svg" width="6000" height="4000" viewBox="0 0 6000 4000" role="img" aria-labelledby="title desc"><title id="title">Dailee — ERD Chen</title><desc id="desc">Diagram ERD Dailee dalam notasi Chen: entitas berbentuk persegi panjang, atribut berbentuk oval, relasi berbentuk belah ketupat, dilengkapi kardinalitas.</desc><defs><linearGradient id="bg" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#FCFBFF"/><stop offset="1" stop-color="#F3EEFF"/></linearGradient><filter id="shadow" x="-15%" y="-15%" width="130%" height="140%"><feDropShadow dx="0" dy="5" stdDeviation="7" flood-color="#40296B" flood-opacity=".12"/></filter></defs><rect width="6000" height="4000" fill="url(#bg)"/>'];
function tx($x,$y,$s,$size=24,$color='#241B35',$weight=500,$anchor='middle'){global $svg;$svg[]='<text x="'.$x.'" y="'.$y.'" text-anchor="'.$anchor.'" font-family="Segoe UI,Arial,sans-serif" font-size="'.$size.'" font-weight="'.$weight.'" fill="'.$color.'">'.htmlspecialchars((string)$s,ENT_QUOTES|ENT_XML1,'UTF-8').'</text>';}
function relation($x1,$y1,$x2,$y2,$rx,$ry,$label,$left='1',$right='N',$dashed=false){global $svg;$c=$dashed?'#E28A29':'#7663A3';$dash=$dashed?' stroke-dasharray="14 10"':'';$svg[]='<path d="M'.$x1.' '.$y1.' L'.$rx.' '.$ry.' M'.($rx+420).' '.$ry.' L'.$x2.' '.$y2.'" fill="none" stroke="'.$c.'" stroke-width="4"'.$dash.'/>';$svg[]='<polygon points="'.($rx+210).','.($ry-70).' '.($rx+420).','.$ry.' '.($rx+210).','.($ry+70).' '.$rx.','.$ry.'" fill="#EEE9FC" stroke="'.$c.'" stroke-width="4" filter="url(#shadow)"/>';
  tx($rx+210,$ry+8,$label,21,'#30244A',650);tx($rx-34,$ry-20,$left,21,$c,700);tx($rx+454,$ry-20,$right,21,$c,700);
}
function relationV($x,$y1,$y2,$label,$top='0..1',$bottom='N',$dashed=false){global $svg;$c=$dashed?'#E28A29':'#7663A3';$dash=$dashed?' stroke-dasharray="14 10"':'';$cy=($y1+$y2)/2;$svg[]='<path d="M'.$x.' '.$y1.' V'.($cy-210).' M'.$x.' '.($cy+210).' V'.$y2.'" fill="none" stroke="'.$c.'" stroke-width="4"'.$dash.'/>';$svg[]='<polygon points="'.$x.','.($cy-210).' '.($x+70).','.$cy.' '.$x.','.($cy+210).' '.($x-70).','.$cy.'" fill="#EEE9FC" stroke="'.$c.'" stroke-width="4" filter="url(#shadow)"/>';
  tx($x,$cy+8,$label,21,'#30244A',650);tx($x+82,$cy-175,$top,21,$c,700,'start');tx($x+82,$cy+190,$bottom,21,$c,700,'start');
}
function entity($name,$cx,$cy,$fields,$legacy=false){global $svg;$ew=300;$eh=94;$x=$cx-$ew/2;$y=$cy-$eh/2;$stroke=$legacy?'#E28A29':'#7558D1';$head=$legacy?'#F6E7D4':'#E1DAFB';$tagFill='#FFF0F0';$left=array_slice($fields,0,(int)ceil(count($fields)/2));$right=array_slice($fields,count($left));$ovalW=290;$ovalH=52;$gap=20;$step=72;
  foreach([[-1,$left],[1,$right]] as [$side,$list]){ $n=count($list);$start=$cy-(($n-1)*$step)/2;$ox=$cx+$side*470; foreach($list as $i=>$raw){$field=$raw[0];$tag=$raw[1]??'';$oy=$start+$i*$step;$bx=$side<0?$ox+$ovalW/2:$ox-$ovalW/2;$ex=$side<0?$x:$x+$ew;$svg[]='<path d="M'.$bx.' '.$oy.' L'.$ex.' '.$cy.'" fill="none" stroke="#9D91B7" stroke-width="2.5"/>';$strokeAttr=$tag==='PK'?'#D66565':'#A99BBB';$fill=$tag==='PK'?$tagFill:'#FFFFFF';$svg[]='<ellipse cx="'.$ox.'" cy="'.$oy.'" rx="'.($ovalW/2).'" ry="'.($ovalH/2).'" fill="'.$fill.'" stroke="'.$strokeAttr.'" stroke-width="2.5"/>';
      $label=$field;$font=19;if(mb_strlen($field)>23)$font=16;tx($ox,$oy+7,$label,$font,'#342C40',500);if($tag==='PK'){$svg[]='<path d="M'.($ox-86).' '.($oy+13).' H'.($ox+86).'" stroke="#D66565" stroke-width="2"/>';}
    }}
  $svg[]='<rect x="'.$x.'" y="'.$y.'" width="'.$ew.'" height="'.$eh.'" fill="#FFFFFF" stroke="'.$stroke.'" stroke-width="4"'.($legacy?' stroke-dasharray="12 7"':'').' filter="url(#shadow)"/><path d="M'.$x.' '.($y+24).' H'.($x+$ew).'" stroke="'.$stroke.'" stroke-width="3"/>';
  $svg[]='<rect x="'.$x.'" y="'.$y.'" width="'.$ew.'" height="30" fill="'.$head.'"'.($legacy?'':'').' />';tx($cx,$cy+9,$name,23,'#30244A',750);
}
tx(150,110,'DAILEE · ENTITY RELATIONSHIP DIAGRAM (NOTASI CHEN)',54,'#5F43B0',750,'start');tx(150,165,'Entitas = persegi panjang  •  Atribut = oval  •  Relasi = belah ketupat  •  Angka pada garis = kardinalitas',25,'#716783',500,'start');
// Relationship diamonds are drawn first so entity/attribute symbols remain readable above them.
relation(940,1710,1900,520,1190,1030,'memiliki','1','N');
relation(940,1740,1900,1220,1190,1510,'mengikuti','1','N');
relation(940,1790,1900,2510,1190,2240,'mengirim / menerima','1','N');
relation(940,1820,1900,3320,1190,3000,'punya jadwal lama','1','N',true);
relation(940,1700,3290,520,2220,700,'membuat agenda','1','N');
relation(940,1820,3290,1760,2220,1780,'membuat momen','1','N');
relationV(3440,567,1713,'ditautkan ke agenda','0..1','N',true);
relation(3740,1760,4770,520,4210,820,'memiliki label','1','N');
relation(3740,1760,4770,1220,4210,1420,'menerima komentar','1','N');
relation(3740,1760,4770,2070,4210,1960,'menerima reaksi','1','N');
relation(3740,1760,4770,3020,4210,2740,'dibagikan','1','N');
// Entity boxes with their attributes as oval nodes.
entity('USERS',800,1760,[['id','PK'],['name'],['email · unique'],['username · unique'],['nama_lengkap'],['password'],['role'],['avatar'],['created_at'],['updated_at'],['location'],['occupation'],['education'],['zodiac_or_interest'],['bio']]);
entity('FRIENDSHIPS',2050,520,[['id','PK'],['user_id','FK*'],['friend_id','FK*'],['status'],['created_at']]);
entity('FOLLOWS',2050,1220,[['id','PK'],['follower_id','FK'],['following_id','FK'],['created_at']]);
entity('CHATS',2050,2510,[['id','PK'],['sender_id','FK*'],['receiver_id','FK*'],['message'],['created_at']]);
entity('ACADEMIC_SCHEDULE',2050,3320,[['id','PK'],['user_id','FK'],['title'],['category'],['due_date'],['is_done'],['created_at']],true);
entity('SCHEDULES',3440,520,[['id','PK'],['user_id','FK*'],['title'],['category'],['description'],['date'],['start_time'],['end_time'],['location'],['status'],['created_at'],['alert_time'],['sync_calendar']]);
entity('MOMENTS',3440,1760,[['id','PK'],['user_id','FK'],['schedule_id','FK*'],['main_image'],['inset_image'],['photo_path'],['caption'],['mood'],['taken_at'],['created_at'],['visibility']]);
entity('MOMENT_TAGS',4920,520,[['id','PK'],['moment_id','FK'],['tag_label']]);
entity('POST_COMMENTS',4920,1220,[['id','PK'],['post_id → moments.id','FK*'],['user_id → users.id','FK*'],['comment'],['created_at']]);
entity('POST_REACTIONS',4920,2070,[['id','PK'],['post_id → moments.id','FK*'],['user_id → users.id','FK*'],['type'],['reaction'],['created_at']]);
entity('POST_SHARES',4920,3020,[['id','PK'],['post_id → moments.id','FK*'],['user_id → users.id','FK*'],['created_at']]);
// Legend and application-flow notes.
$svg[]='<rect x="150" y="3650" width="5700" height="240" rx="15" fill="#FFFFFF" stroke="#D9D0EA" stroke-width="3"/>';
tx(190,3705,'KETERANGAN',23,'#5F43B0',750,'start');tx(190,3750,'PK = primary key (digarisbawahi)   ·   FK = relasi foreign key   ·   FK* = relasi logis dari kolom yang dipakai aplikasi, belum dipastikan sebagai constraint database',20,'#302A3B',500,'start');tx(190,3795,'Feed, notifikasi jadwal, dan rekap bulanan dihitung dari query (bukan tabel tersendiri). Calendar/rekap memakai moments.created_at + schedules.start_time/status.',20,'#302A3B',500,'start');tx(190,3840,'academic_schedule adalah tabel lama, terpisah dari schedules yang dipakai fitur Agenda/Todo. post_id pada tabel interaksi menunjuk ke moments.id.',20,'#716783',500,'start');
$svg[]='</svg>';file_put_contents($file,implode("\n",$svg));echo "Wrote $file\n";
