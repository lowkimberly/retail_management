-- Kimberly Low
-- Project 3

-- 1. Write a stored procedure to show the tuples in each table.
-- Every time you do create procedure, it HAS to begin and end with delimiter
delimiter //
create procedure show_customers()
begin
	select * from customers;
end //

create procedure show_employees()
begin
	select * from employees;
end //

create procedure show_logs()
begin
	select * from logs;
end //

create procedure show_products()
begin
	select * from products;
end //

create procedure show_purchases()
begin
	select * from purchases;
end //

create procedure show_suppliers()
begin
	select * from suppliers;
end //
delimiter ;

-- 2. Write a procedure to report the monthly sale information for any given product.
-- Gets name, month, total qty, and amount, then divides for average price.
-- Group to keep those in same month together (Same month in different years will be
-- different rows.
delimiter //
create procedure report_monthly_sale( in prod_id varchar(4))
begin
	declare no_id_error condition for sqlstate '45000';
	declare rowcount int;
	select count(*) into rowcount from products where products.pid = prod_id;
	if rowcount < 1 then
		signal no_id_error set message_text = 'Cannot find that id!';
	end if;

select products.pname, date_format(purchases.ptime, '%b') as mon , date_format(purchases.ptime, '%Y') as yr,sum(purchases.qty) as total_qty,
	sum(purchases.total_price) as total_amount, sum(purchases.total_price)/sum(purchases.qty) as average_price
	from purchases, products where purchases.pid = products.pid	and products.pid = prod_id group by yr, mon desc;



end //
delimiter ;

-- 3. Write procedures to add tuples into the purchases table and the products table. 
-- purchases: cid, eid, pid, qty, ptime, total_price
-- ptime is current time, compute total_price, auto incr. pur#.
-- error checking for purchases done by triggers
delimiter //
create procedure add_purchase( in purno varchar(4), in c_id varchar(4), in e_id varchar(3), in p_id varchar(4), in pur_qty int(5))
begin
	declare discounted decimal(7,2);
	select (original_price * (1-discnt_rate)) into discounted from products where pid = p_id;
	insert into purchases (`pur#`, cid, eid, pid, qty, ptime, total_price)
	values( purno, c_id, e_id, p_id, pur_qty, current_timestamp(), discounted  );
end //

delimiter ;

-- product: pame, qoh, qoh_threshold, original_price, discnt_rate, sid
-- Provide own 4 character pid, not in use by any other product
delimiter //
create procedure add_product( in a_pid varchar(4), in a_pname varchar(15), in a_qoh int(5), in a_qoh_threshhold int(5),  
				in a_original_price decimal(6,2), in a_discnt_rate decimal(3,2), in a_sid varchar(2))
begin
	declare my_error condition for sqlstate '45000';
	declare rowcount int;

 	if a_pid=NULL then
 		signal my_error set message_text = 'Pick a new product id.';
	end if;

	select count(*) into rowcount from products where products.pid = a_pid;
	if rowcount >0 then
		signal my_error set message_text = 'That id is already being used!';
	end if;

	-- qoh > qoh threshhold
	if a_qoh < a_qoh_threshhold then
		signal my_error set message_text = 'You must have more quantity than the threshhold amount.';
	end if;

	-- sid exists
	select count(*) into rowcount from suppliers where suppliers.sid = a_sid;
	if rowcount < 1 then
		signal my_error set message_text = 'Cannot find that supplier.';
	end if;

	-- Everything is okay, do the insert
	insert into products (pid, pname, qoh, qoh_threshold, original_price, discnt_rate, sid) 
	values( a_pid, a_pname, a_qoh, a_qoh_threshhold, a_original_price, a_discnt_rate, a_sid);
end //

delimiter ;

-- 4. Add a tuple to the logs table automatically whenever any table is modified.
-- after insert on purchases, insert into logs. We also use this trigger for changing qoh,
-- so it contains some other things to do.

-- 6. Reduce qoh if we go below the threshhold. Save the old value for PHP to show the correct messages.
-- Also increase customer visit.

delimiter //
create trigger trigger_on_purchase
after insert on purchases
for each row
begin

declare old_qoh int;
declare inc_qoh int;
declare prod_threshhold int;

-- THIS IS THE INSERT INTO LOGS STATEMENT
insert into logs (who, time, table_name, operation, key_value) 
values(user(), current_timestamp, 'purchases', 'insert', LAST_INSERT_ID());

-- This is part 6.
select qoh into old_qoh from products where pid = new.pid; -- old qoh
select qoh_threshold into prod_threshhold from products where pid = new.pid; -- product threshhold

-- went below threshhold? Double 
if ((old_qoh - new.qty) < prod_threshhold)
then
    update products set qoh = 2*old_qoh where pid = new.pid; -- double the before-subtraction qty
    set @old_qty := old_qoh; -- variable is here!
    set @inc_qty := old_qoh+new.qty; 
else
    update products set qoh = qoh - new.qty where pid = new.pid; -- old qoh - however many where bought, if we are above threshhold
    set @old_qty=-1; -- use for message printing
    set @inc_qty=-1;
end if;

-- Update number of visits and timestamp
update customers set visits_made = visits_made+1, last_visit_time=current_timestamp where cid = new.cid;

end //
delimiter ;


-- On products update, insert into log.
delimiter //
create trigger trigger_on_products
after update
on products
for each row
begin
insert into logs (who, time, table_name, operation, key_value) 
values(user(), current_timestamp, 'products', 'update', new.pid);
end //
delimiter ;

-- On customers, insert into log
delimiter //
create trigger trigger_on_customers
after update
on customers
for each row
begin
insert into logs (who, time, table_name, operation, key_value) 
values(user(), current_timestamp, 'customers', 'update', new.cid);
end //

delimiter ;

--- 5. Check quantity being purchased is actually less than qoh.
-- before inserting anything. If not enough quantity, we don't want it to work
-- so we force an error to prevent the purchase from being added at all.
-- Also extra error messages for wrong values.

delimiter //
create trigger trigger_before_purchases
before insert
on purchases
for each row
begin
	declare purno condition for sqlstate '45000';
	declare qty_error condition for sqlstate '45000';
	declare c_error condition for sqlstate '45000';
	declare e_error condition for sqlstate '45000';
	declare p_error condition for sqlstate '45000';
	declare  q, qt int;
	declare my_cid, my_eid, my_pid, my_purno int;

	-- qty check
	select qoh into q from products where pid = new.pid;
	if q < new.qty then
		signal qty_error set message_text = 'Not enough quantity!';
	end if;

	if new.qty = 0 then
		signal qty_error set message_text = 'How can you buy 0 items?';
	end if;

	-- valid ids
 	select count(*) into my_cid from customers where cid = new.cid;
 	if my_cid < 1 then
 		signal c_error set message_text = 'That customer does not exist.';
 	end if;
	select count(*) into my_eid from employees where eid = new.eid;
	if my_eid < 1 then
		signal e_error set message_text = 'That employee does not exist.';
	end if;
	select count(*) into my_pid from products where pid = new.pid;
	if my_pid < 1 then
		signal p_error set message_text = 'That product does not exist.';
	end if;

 	if new.`pur#`=NULL then
 		signal purno set message_text = 'Pick a purchase number.';
	end if;
 	select count(*) into my_purno from purchases where `pur#` = new.`pur#`;
 	if my_purno >0 then
 		signal purno set message_text = 'That purchase number has already been used.';
	end if;

end //
delimiter ;
