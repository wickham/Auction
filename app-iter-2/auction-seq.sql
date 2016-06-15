-- auction-seq.sql -- SQL definitons for sequence generation
-- Auction Web Application Project
--
-- C S 105: PHP/SQL, Spring 2014, J. Thywissen
-- The University of Texas at Austin
--

CREATE TABLE SEQ (
    NAME VARCHAR(30) PRIMARY KEY,
    CURRENT_VALUE INTEGER NOT NULL );


-- The use of LAST_INSERT_ID is a MySQL-specific trick to
-- eliminate the need for an explicit transaction here.

-- From: Zaitsev, Peter. "Stored function to generate sequences". MySQL
--   Performance Blog. Pleasanton, Calif.: Percona LLC, 2008 Apr 2.
--   URL: http://www.mysqlperformanceblog.com/2008/04/02/stored-function-to-generate-sequences/

delimiter //
CREATE FUNCTION NEXT_SEQ_VALUE(SEQ_NAME VARCHAR(30))
    RETURNS INT
    MODIFIES SQL DATA
BEGIN
    UPDATE SEQ
        SET
            CURRENT_VALUE = LAST_INSERT_ID(CURRENT_VALUE+1)
        WHERE NAME = SEQ_NAME;
    RETURN LAST_INSERT_ID();
END
//
delimiter ;


INSERT INTO SEQ SELECT 'PERSON', MAX(PERSON_ID) FROM PERSON;
INSERT INTO SEQ SELECT 'AUCTION_STATUS', MAX(AUCTION_STATUS_ID) FROM AUCTION_STATUS;
INSERT INTO SEQ SELECT 'ITEM_CATEGORY', MAX(ITEM_CATEGORY_ID) FROM ITEM_CATEGORY;
INSERT INTO SEQ SELECT 'AUCTION', MAX(AUCTION_ID) FROM AUCTION;
INSERT INTO SEQ SELECT 'BID', MAX(BID_ID) FROM BID;
