<h1>Deploying Latest Code</h1>
<?php
// shell_exec('cd /var/www/html/ && /usr/bin/git stash && /usr/bin/git pull origin master');
echo '<br>';
function execPrint($command) {
    $result = array();
    exec($command, $result);
    foreach ($result as $line) {
        print($line . "\n");
    }
}
// Print the exec output inside of a pre element
print("<pre>" . execPrint("pwd") . "</pre>");
print("<pre>" . execPrint("/usr/bin/git stash") . "</pre>");
print("<pre>" . execPrint("/usr/bin/git stash") . "</pre>");
print("<pre>" . execPrint("/usr/bin/git pull origin master") . "</pre>");
print("<pre>" . execPrint("/usr/bin/git stash") . "</pre>");
?>
