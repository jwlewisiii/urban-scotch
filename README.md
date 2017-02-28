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
            <th>Options</th>
            <th>Arguments</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Synchronize Database</td>
            <td>Updates local database to be equivalent with a remote database by the same name.</td>
            <td><pre> php urban-scotch database:sync-db <database> </pre></td>
            <td>Database</td>
        </tr>
    </tbody>
</table>
