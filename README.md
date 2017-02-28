# urban-scotch

A command line tool to help keep your local database up-to-date with a remote database.

Requires PHP's ssh2 extension.

Currently only works with MySQL databases.

<h2>Commands</h2>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Syntax</th>
            <th>Arguments</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Synchronize Database</td>
            <td>Updates local database to be equivalent with a remote database by the same name.</td>
            <td><pre> php urban-scotch database:sync-db <database> </pre></td>
            <td>database</td>
            <td>N/A</td>
        </tr>
        <tr>
            <td>Synchronize Table</td>
            <td>Updates local database table to be equivalent with a remote database table by the same name.</td>
            <td><pre> php urban-scotch database:sync-t <database> <table> </pre></td>
            <td>database, table</td>
            <td>N/A</td>
        </tr>
    </tbody>
</table>
