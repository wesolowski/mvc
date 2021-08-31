{include file="base/header.tpl" title="Backend Login"}
<h1>Backend Login</h1>
<hr/>
<form action="backend.php?page=BackendLogin" method="post">
    <table>
        <tr>
            <td>Username</td>
            <td><input type="text" name="username" value="{$username}" placeholder="maxmustermann" required /></td>
        </tr>
        <tr>
            <td>Passwort</td>
            <td><input type="password" name="password" placeholder="123456789" required /></td>
        </tr>
        <tr>
            <td><input type="submit" name="login" value="login" /></td>
            <td><input type="reset" /></td>
        </tr>
    </table>
</form>
{include file="base/footer.tpl"}