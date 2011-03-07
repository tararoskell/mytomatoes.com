class CreateTomatoesTable
  def up(db)
    db.query <<-SQL
CREATE TABLE Tomatoes (
  id                INT UNSIGNED                 NOT NULL AUTO_INCREMENT,
  account_id        INT UNSIGNED                 NOT NULL,
  status            ENUM ('started', 'completed', 'squashed') NOT NULL DEFAULT 'started',
  description       VARCHAR(255),
  local_start       TIMESTAMP,
  local_end         TIMESTAMP,
  created_at        TIMESTAMP,
  updated_at        TIMESTAMP,
  PRIMARY KEY (id)
);
SQL
  end
  
  def down(db)
    db.query "DROP TABLE IF EXISTS Tomatoes"
  end
  
end