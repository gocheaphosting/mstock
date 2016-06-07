<?
if(!defined("site_root")){exit();}

function sql_input ($input) 
{
  //$input=preg_replace('/union/i', '_union_', $input);
  $input=preg_replace('/load_file/i', '', $input);
  $input=preg_replace('/outfile/i', '', $input);
  $input=preg_replace('/--/i', '-', $input);
  $input=preg_replace('/BENCHMARK/i', '', $input);
  $input=preg_replace('/0x/i', '_0x_', $input);
  $input=preg_replace('/#/i', '_resh_', $input);
  $input=preg_replace('/CONCAT/i', '_concat_', $input);
  $input=preg_replace('/cmd/i', '_cmd_', $input);
  $input=preg_replace('/exec/i', '_exec_', $input);

  return $input;
}


function sql_out ($input) 
{
  $input=preg_replace('/_qt_/i', "'", $input);
  //$input=preg_replace('/_union_/i', 'union', $input);
  $input=preg_replace('/_0x_/i', '0x', $input);
  $input=preg_replace('/_char_/i', 'char', $input);
  $input=preg_replace('/_delete_/i', 'delete', $input);
  $input=preg_replace('/_drop_/i', 'drop', $input);
  $input=preg_replace('/_update_/i', 'update', $input);
  $input=preg_replace('/_insert_/i', 'insert', $input);
  $input=preg_replace('/_alter_/i', 'alter', $input);
  $input=preg_replace('/_select_/i', 'select', $input);
  $input=preg_replace('/_resh_/i', '#', $input);
  $input=preg_replace('/_concat_/i', 'concat', $input);
  $input=preg_replace('/_cmd_/i', 'cmd', $input);
  $input=preg_replace('/_exec_/i', 'exec', $input);

  return $input;
}

class TMySQLConnection
{
  var $connection;

  function connect()
  {
  	$this->connection = mysqli_connect(dbhost,dbuser,dbpassword,dbname); 
  }
  
  function execute($query)
  { 	
	if($mysqli_result = mysqli_query($this->connection,sql_input($query))) 
	{
		return $mysqli_result;
	}
	else
	{
		return false;
	}
  }
  
  function close()
  {
    mysqli_close($this->connection);
  }
}

class TMySQLQuery
{
  var $connection; 
  var $result;
  var $row;
  var $trow;
  var $eof;        
  var $addnew;
  var $source; 
  var $rc;    

  function TMySQLQuery()
  {
    $this->connection = new TMySQLConnection;
  }

  function open( $query )
  {  
    $this->result = $this->connection->execute( $query );
    $this->movenext();
  }

  function movenext()
  {
    if ( $this->row = @mysqli_fetch_assoc($this->result ) ) 
    {
		foreach ($this->row as $rkey => $rvalue)
		{
			$this->row[$rkey]=stripslashes(sql_out($rvalue));
		}

      	$this->eof = false;
    }
    else 
    {
      	$this->eof = true;
    }
    $this->trow = $this->row;
    $this->rc=@mysqli_num_rows($this->result); 
  }

  function close()
  {
    $result->close(); 
    @mysqli_free_result($this->result);
    unset( $this->result );
    unset( $this->connection );
  }
}


?>