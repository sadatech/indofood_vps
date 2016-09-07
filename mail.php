<?php
$to  = 'rizaldi354313@gmail.com' . ', ';
$to .= 'rizal@sada.co.id';

$subject = 'Birthday Reminders for August';

$message = '
<html>
<head>
  <title>Birthday Reminders for August</title>
</head>
<body>
  <p>Here are the birthdays upcoming in August!</p>
  <table>
    <tr>
      <th>Person</th><th>Day</th><th>Month</th><th>Year</th>
    </tr>
    <tr>
      <td>Joe</td><td>3rd</td><td>August</td><td>1970</td>
    </tr>
    <tr>
      <td>Sally</td><td>17th</td><td>August</td><td>1973</td>
    </tr>
  </table>
</body>
</html>
';

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$headers .= 'To: rifqisec <rifqisec@gmail.com>' . "\r\n";
$headers .= 'From: Rizaldi <rizaldichozzone@gmail.com>' . "\r\n";

mail($to, $subject, $message, $headers);
?>