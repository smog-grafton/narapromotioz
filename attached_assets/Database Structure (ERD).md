📄 Nara Promotionz Boxing Promotions Website - Database Structure (ERD)
📌 Tables
1. users
id (PK)

name

email (unique)

password

phone_number

profile_picture (nullable)

user_type (enum: 'customer', 'admin')

email_verified_at (timestamp, nullable)

remember_token

created_at

updated_at

2. fighters
id (PK)

full_name

nickname (nullable)

date_of_birth

nationality

height_cm (nullable)

weight_kg (nullable)

weight_class

boxing_style (orthodox, southpaw, etc.)

wins

losses

draws

ko_wins

profile_image

bio (longtext)

created_at

updated_at

3. events
id (PK)

title

slug (unique)

event_date

location

event_banner

ticket_price

description

live_stream_url (nullable) (private YouTube link or other service)

is_live (boolean, default false)

created_at

updated_at

4. fights
id (PK)

event_id (FK → events.id)

fighter_one_id (FK → fighters.id)

fighter_two_id (FK → fighters.id)

result (nullable) (TBD, Fighter One Win, Fighter Two Win, Draw)

fight_order (integer) (order on fight card)

created_at

updated_at

5. news_articles
id (PK)

title

slug (unique)

thumbnail_image

content (longtext)

seo_title

seo_description

seo_keywords

published_at

created_at

updated_at

6. tickets
id (PK)

event_id (FK → events.id)

user_id (FK → users.id)

ticket_number (unique)

amount_paid

payment_status (enum: 'pending', 'paid', 'failed')

payment_method (Pesapal, AirtelMoney, MTNMoney)

created_at

updated_at

7. stream_access
id (PK)

event_id (FK → events.id)

user_id (FK → users.id)

has_access (boolean, default false)

payment_reference (nullable)

created_at

updated_at

8. payments
id (PK)

user_id (FK → users.id)

payment_gateway (Pesapal, AirtelMoney, MTNMoney)

amount

status (pending, completed, failed)

transaction_reference

transaction_date

created_at

updated_at

9. rankings
id (PK)

fighter_id (FK → fighters.id)

division (ex: Lightweight, Welterweight)

rank_position

points (optional)

created_at

updated_at

🎯 Relationships:
A User can buy many Tickets

A User can get Stream Access for paid events

An Event has many Fights (Fight Card)

A Fight has two Fighters (fighter_one and fighter_two)

Fighters can appear in multiple Fights

Users can make multiple Payments

A Fighter has one Ranking per division

Events can be streamed if the user has paid