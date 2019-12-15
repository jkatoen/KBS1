<?php
include("PHP/connectdb.php");
include("PHP/functions.php");
include ("header.php");
?>
<head>
<body>
<div class="leftcolumn">
</div>
<div class="midcolumn" style="margin-left: 20%">
    <div class="card" style="text-align: center">
    <form style="margin-left: 40%" method="post" action="payment.php">
        <table style="width: 35%">
            <th>Kies uw bank</th>
            <tr><td><input type="radio" value="bank" name="test">test</td></tr>
            <tr><td><input type="radio" value="bank" name="test">test</td></tr>
            <tr><td><input type="radio" value="bank" name="test">test</td></tr>
            <tr><td><input type="radio" value="bank" name="test">test</td></tr>
            <tr><td><input type="radio" value="bank" name="test">test</td></tr>
            <tr><td><input type="radio" value="bank" name="test">test</td></tr>
            <tr><td><input type="radio" value="bank" name="test">test</td></tr>
            <tr><td><input type="submit" value="betaal" name="test"></td></tr>
        </table>
    </form>
    </div>
</body>
