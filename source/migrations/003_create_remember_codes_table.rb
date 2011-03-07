class CreateRememberCodesTable
  def up(db)
    db.query <<-SQL
CREATE TABLE RememberCodes (
  code              VARCHAR(64)                   NOT NULL,
  account_id        INT UNSIGNED                  NOT NULL,
  created_at        TIMESTAMP                     NOT NULL,
  
  PRIMARY KEY (code)
);
SQL
  end
  
  def down(db)
    db.query "DROP TABLE IF EXISTS RememberCodes"
  end
  
end