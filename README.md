# urban-scotch

A command line tool to help keep your local database up-to-date with a remote database.

Requires PHP's ssh2 extension.

Currently only works with MySQL databases.

<h2>Getting Started</h2>
Clone the repo.
<pre>git clone https://github.com/jwlewisiii/urban-scotch.git</pre>
Install composer packages.
<pre>cd urban-scotch/ && composer install</pre>
Set up config file by changing .env.example to .env and setting the variables.
<pre>mv .env.example .env</pre>
Add symbolic link to path (Optional).
<pre>ln -s /path/to/urban-scotch /path/to/symlink</pre>
Now you can easily interface with a remote database using urban-scotch
<pre>urban-scotch database:sync-db database </pre>

<h2>Commands</h2>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Syntax</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Synchronize Database</td>
            <td>Updates local database to be equivalent with a remote database by the same name.</td>
            <td><pre> php urban-scotch database:sync-db {database} </pre></td>
        </tr>
        <tr>
            <td>Synchronize Table</td>
            <td>Updates local database table to be equivalent with a remote database table by the same name.</td>
            <td><pre> php urban-scotch database:sync-t {database} {table} </pre></td>
        </tr>
        <tr>
            <td>List Tables</td>
            <td>Displays a list of tables from a remote database.</td>
            <td><pre> php urban-scotch database:list-t {database} </pre></td>
        </tr>
        <tr>
            <td>Execute Command</td>
            <td>Executes a SQL query on a database.</td>
            <td><pre> php urban-scotch database:exec {database} '{database}' </pre></td>
        </tr>
    </tbody>
</table>
