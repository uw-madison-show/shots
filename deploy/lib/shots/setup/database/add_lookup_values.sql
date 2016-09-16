delete from "lookup_values";


-- people: category = Faculty|Researcher|Public Health Officer|Student|Staff|Organization
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('people','category','faculty','Faculty');
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('people','category','researcher','Researcher');
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('people','category','public','Public Health Officer');
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('people','category','student','Student');
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('people','category','staff','Staff');
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('people','category','organization','Organization');


-- events: type = Meeting|Reminder|Deadline|Outreach
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('events','type','meeting','Meeting');
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('events','type','reminder','Reminder');
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('events','type','deadline','Deadline');
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('events','type','outreach','Outreach');


-- grants: status = Preliminary Data Analysis|Writing the Applicaiton|Submitted|Revise and Resubmit|Funded|Rejected
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('grants','status','preliminary','Preliminary Data Analyis');
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('grants','status','writing','Writing the Application');
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('grants','status','submitted','Submitted');
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('grants','status','revise','Revise and Resubmit');
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('grants','status','funded','Funded');
INSERT INTO "lookup_values" ("table_name","column_name","lookup_value","label") VALUES ('grants','status','rejected','Rejected');
