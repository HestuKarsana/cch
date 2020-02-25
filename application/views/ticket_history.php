<!--
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>History Pengaduan</title>

  <style type="text/css">
  </style>    
</head>
<body style="margin:0; padding:0; background-color:#F2F2F2;">
  <center>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F2F2F2">
        <tr>
            <td align="center" valign="top">
                
                
            </td>
        </tr>
    </table>
  </center>
</body>
</html>
-->
<?php
$row = $ticket->row();
#if($ticket->num_rows() > 0)
#{
#    foreach($ticket->result() as $row)
#    {
#        echo '<tr><td>'.$row->date.'</td><td>'.nl2br($row->complaint).'</td><td>'.$row->assignee_val.'</td><td>Entri</td></tr>';
#    }
#}
?>
<section>
    <table border="1" width="100%">
        <tr><td>No Ticket</td><td><?php echo $row->no_ticket;?></td>
            <td>Tanggal Aduan</td><td><?php echo $row->date;?></td></tr>
        <tr><td>No Barcode / AWB</td><td><?php echo $row->awb;?></td>
            <td>Tujuan Aduan</td><td><?php echo $row->assignee;?></td></tr>
    </table>
    <br/>
    <table border="1" width="100%">
        <thead>
            <tr>
                <td>Tanggal Update</td><td>Komplain / Balasan</td><td>Kantor</td><td>Status</td>
            </tr>
        </thead>
        <tbody>
            <?php
            echo '<tr><td>'.$row->date.'</td><td>'.nl2br($row->complaint).'</td><td>'.$row->assignee_val.'</td><td>Entri</td></tr>';
            
            if($response->num_rows() > 0)
            {
                foreach($response->result() as $row)
                {
                    echo '<tr><td>'.$row->date.'</td><td>'.nl2br($row->response).'</td><td>'.$row->update_office.'</td><td>'.$row->status_name.'</td></tr>';
                }
            }
            ?>

        </tbody>
    </table>
</section>