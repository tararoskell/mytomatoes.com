class CreateEventLogTable
  def up(db)
    db.query <<-SQL
CREATE TABLE EventLog (
  id                INT UNSIGNED                 NOT NULL AUTO_INCREMENT,
  event             VARCHAR(255)                 NOT NULL,
  ip_address        VARCHAR(15)                  NOT NULL,
  account_id        INT UNSIGNED,
  details           VARCHAR(255),
  time              TIMESTAMP,
  
  PRIMARY KEY (id)
);
SQL
  end
  
  def down(db)
    db.query "DROP TABLE IF EXISTS EventLog"
  end
  
end