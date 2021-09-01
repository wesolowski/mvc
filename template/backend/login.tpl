{include file="base/header.tpl" title="Backend Login"}
<h1>Backend Login</h1>
<hr/>
<form action="index.php?page=Login&area=Admin" method="post">
    <table>
        <tr>
            <td>Username</td>
            <td><input type="text" name="username" value="{$username|default:''}" placeholder="maxmustermann" /></td>
        </tr>
        <tr>
            <td>Passwort</td>
            <td><input type="password" name="password" placeholder="123456789" /></td>
        </tr>
        <tr>
            <td><input type="submit" name="login" value="login" /></td>
            <td><input type="reset" /></td>
        </tr>
    </table>
</form>
<h3>{$errorh3|default:''}</h3>
<ul>
    {foreach from=$errors|default:null item=error}
        <li>{$error}</li>
    {/foreach}
</ul>
{include file="base/footer.tpl"}