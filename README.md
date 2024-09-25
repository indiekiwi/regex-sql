# regex-sql

A web based tool to parse row based text data via a regular expression into a temporary database using Sqlite so it can be quried with SQL.

## Tool UI

<table>
  <tr>
    <td width="70%" rowspan="6"> <img src="readme/data.png"> </td>
    <th width="5%">Section</th>
    <th width="25%">Description</th>
  </tr>
  <tr>
    <td>Data</td>
    <td>Raw text which can may be pasted or uploaded using a file</td>
  </tr>
  <tr>
    <td>Regex</td>
    <td>The regular expression to define the structure using capture groups; round brackets: ()</td>
  </tr>
  <tr>
    <td>SQL Query</td>
    <td>A SQL select statement based on the other sections</td>
  </tr>
  <tr>
    <td>Table Name</td>
    <td>The default table name can be customised with an alias</td>
  </tr>
  <tr>
    <td>Capture Groups</td>
    <td>Map Regex Capture Group indices to MySql columns</td>
  </tr>
</table>

## Output

![](readme/result.png)
