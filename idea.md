# MelodyVault: Digital Music Ownership Platform

This document outlines the core concept, data models, and feature set of MelodyVault, an application designed to simulate the purchase and ownership of digital music tracks and albums using multiple virtual currencies.

# Application Concept

MelodyVault serves as a demonstration of a robust backend system for managing user accounts, currency balances, and item ownership. It utilizes a clean architectural approach (simulated MVC/Service Layer) running on a single Dart Shelf server.

Key Goals

Authentication: Secure registration and login for users.

Commerce: Allow users to purchase music using different credit types.

Ownership: Track which songs and albums each user legally owns.

Scalability: Separate data access logic from the routing/presentation layer (DataService).

# Core Features & Business Logic

Commerce & Transactions

Credit Top-Up: Allows users to add Gold and Silver credits (simulated cash purchase).

Buy Song: A user can purchase a song using Silver Credits or Cash. If successful, the corresponding currency is deducted, and the song ID is added to ownedSongIds.

Buy Album: A user can purchase an entire album using Gold Credits or Cash. If successful, all songs associated with that album ID are added to the user's ownedSongIds.

Download Music: A user can download any of their own songs that they own.

Borrowing Music: A user can lend music to another user to listen too for a week, the borrowing user can not download it but may stream it.

Music Trails: A user can taste test a song for a 30sec snippet before they buy the song.

Subscription: A user can sign up for cash each month, they stream any song but still only download music that they own, the user will get some gold credits and some silver too each month, the user will only get a max automatic top up of credits, but they can still add some manually. 


# User Management

CRUD Methods: register, login, and deleteUser are implemented in the DataService.

Library View: The home page renders the user's owned music library by referencing ownedSongIds.

# Architecture and Technology

* Server: Dart Shelf

* Routing: shelf_router (Handles / GET/POST requests)

* Database: apache's mysql (localhost)

* Service Layer: DataService (Business logic, full CRUD for all models)

* Presentation: HTML templates from documents, with dynamically created HTML from dart

* Styling: styles.scss (Pre-compiled CSS constant)


