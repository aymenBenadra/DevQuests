# DevQuests

<!--
'########::'########:'##::::'##::'#######::'##::::'##:'########::'######::'########::'######::
 ##.... ##: ##.....:: ##:::: ##:'##.... ##: ##:::: ##: ##.....::'##... ##:... ##..::'##... ##:
 ##:::: ##: ##::::::: ##:::: ##: ##:::: ##: ##:::: ##: ##::::::: ##:::..::::: ##:::: ##:::..::
 ##:::: ##: ######::: ##:::: ##: ##:::: ##: ##:::: ##: ######:::. ######::::: ##::::. ######::
 ##:::: ##: ##...::::. ##:: ##:: ##:'## ##: ##:::: ##: ##...:::::..... ##:::: ##:::::..... ##:
 ##:::: ##: ##::::::::. ## ##::: ##:.. ##:: ##:::: ##: ##:::::::'##::: ##:::: ##::::'##::: ##:
 ########:: ########:::. ###::::: ##### ##:. #######:: ########:. ######::::: ##::::. ######::
........:::........:::::...::::::.....:..:::.......:::........:::......::::::..::::::......:::
-->

DevQuests is a simple, open-source, community-driven platform for developers to create and share roadmaps for their careers.

## TABLE OF CONTENT

- [DevQuests](#devquests)
  - [TABLE OF CONTENT](#table-of-content)
  - [OVERVIEW](#overview)
    - [Project Glossary](#project-glossary)
  - [PROJECT TEAM](#project-team)
  - [PHASES](#phases)
  - [CONTENT STRUCTURE](#content-structure)
    - [Site map](#site-map)
    - [Content types](#content-types)
    - [Taxonomies](#taxonomies)
  - [MODELIZATION](#modelization)
    - [Use Case Diagram](#use-case-diagram)
    - [Class Diagram](#class-diagram)
  - [DESIGN](#design)
    - [Graphic Charter](#graphic-charter)
  - [TECHNOLOGICAL CHOICES](#technological-choices)
    - [Modelization](#modelization-1)
    - [Frontend](#frontend)
    - [Backend](#backend)
    - [Database](#database)
    - [Tools](#tools)
      - [Modelization tool](#modelization-tool)
      - [Planning Tool](#planning-tool)
      - [Design Tool](#design-tool)
      - [Package Managers](#package-managers)
      - [Build Tools](#build-tools)
      - [Git & Version Control](#git--version-control)
  - [FUNCTIONALITY](#functionality)
    - [Primary Functionalities](#primary-functionalities)
    - [Secondary Functionalities](#secondary-functionalities)
  - [ACCESSIBILITY](#accessibility)
  - [BROWSER AND DEVICE SUPPORT](#browser-and-device-support)
  - [HOSTING](#hosting)
  - [ONGOING SUPPORT AND MAINTENANCE](#ongoing-support-and-maintenance)
  - [ASSUMPTIONS](#assumptions)
  - [MILESTONES](#milestones)
  - [DEADLINES](#deadlines)
  - [Conclusion & Perspectives](#conclusion--perspectives)

## OVERVIEW

DevQuests is for whoever wants to learn programming to use as an interactive guide or a mentor in a way, where users can make sure they're making progress in the best way possible and that they're going on the right path for their dream career without the need to pay a large sum of money to some online or in-person Bootcamp.

DevQuests follows the learning by doing method by offering Detailed Roadmaps\* with required and optional Modules\*, selected Resources\* that will surely help the user understand what the module is about.

### Project Glossary

- **_Roadmaps_**: Career paths which the user can follow.
- **_Modules_**: Skills needed for a specific roadmap.
- **_Nodes_**: Skills needed for a specific module.
- **_Resources_**: Ressources to learn a skill or a set of skills.
- **_Interview Questions_**: Questions to help the user understand the modules.

## PROJECT TEAM

- Mohammed-Aymen Benadra (#aymenBenadra) – CEO / Developer / Designer – aymanbenadra16@gmail.com

## PHASES

1. **Basic Roadmap web app**:
   - Users can _browse_ and _choose_ a Roadmap.
   - Users can _browse_ and _select_ a Module.
   - Users can _browse_ and _visit_ a Resource.
   - Users can _answer_ an Interview Question.
2. **Adding Content**:
   1. Add _Roadmaps_.
   2. Add _Resources_ on each _Module_.
   3. Add _Interview Questions_.
3. **Adding Secondary Functionalities**:
   1. Authentification for saving progress.
   2. Add Learning Mode for time tracking (Relaxed/Timed).

## CONTENT STRUCTURE

### Site map

- [Home](#home)
  - [Login](#login)
  - [Signup](#signup)
  - [Logout](#logout)
- [Roadmap](#roadmap)
- [404](#404)

### Content types

- User: Timeless
- Roadmap: Timeless
- Module: Roadmap order
- Resource: Timeless
- Interview Question: Timeless

### Taxonomies

- **Learning Mode**
  - Relaxed
  - Normal
  - Hardcore
- **Recommendation**
  - Required
  - Recommended
  - Optional

## MODELIZATION

### Use Case Diagram

![Use Case Diagram](./Modelization/use_case_diagram.png)

### Class Diagram

![Class Diagram](./Modelization/class_diagram.png)

## DESIGN

### Graphic Charter

[Graphic Charter](https://www.figma.com/file/UtTIub4HNUiwNsEqmdtoMR/DevQuests-Graphic-Charter?node-id=0%3A1)

## TECHNOLOGICAL CHOICES

### Modelization

- **UML**

### Frontend

- **Html 5**
- **CSS 3**
  - **TailwindCSS**
  - **Daisy UI**
- **Javascript ES6**
- **React.js**
  - **React Router**
  - **React Context**
  - **React Query**

### Backend

- **PHP** -> [_SakamotoMVC_](https://github.com/aymenBenadra/SakamotoMVC)

### Database

- **SQL** -> _MySQL_

### Tools

#### Modelization tool

- **Draw.io**

#### Planning Tool

- **Trello**

#### Design Tool

- **Figma**

#### Package Managers

- **Yarn** -> _Frontend_
- **Composer** -> _Backend_

#### Build Tools

- **Vite** -> _Builder_
- **Webpack** -> _Bundler_
- **Babel** -> _Transcompiler_
- **ESLint** -> _Linter_

#### Git & Version Control

- **Git** -> _GitHub_

## FUNCTIONALITY

There are many functionalities that needs to be implemented for the project to be concidered done, and we can devide them to Primary and Secondary functionalities.

### Primary Functionalities

|  Fonctionality   |                                                                                            _FN0001: Choose Roadmap_                                                                                             |
| :--------------: | :-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------: |
|   **Objectif**   |                                                                                      User can Browse and Choose a Roadmap                                                                                       |
| **Description**  | A Grid-view of Roadmap cards, each has information about the Roadmap and how much time needed to complete it. Users can Choose one Roadmap at a time and if they want to change it later a warning will appear. |
| **Constraints**  |                                                                                                        -                                                                                                        |
| **Dependencies** |                                                                                                        -                                                                                                        |
|   **Priority**   |                                                                                                      High                                                                                                       |

|  Fonctionality   |                                                     _FN0002: Select Module_                                                      |
| :--------------: | :------------------------------------------------------------------------------------------------------------------------------: |
|   **Objectif**   |                                        User can Browse and Select a module from a Roadmap                                        |
| **Description**  | A Module is a Skill or a set of Skills that the user needs to learn, each skill should show information, Resources when selected |
| **Constraints**  |                                                                -                                                                 |
| **Dependencies** |                                                              FN0001                                                              |
|   **Priority**   |                                                               High                                                               |

|  Fonctionality   |                                                                                                                                               _FN0003: Authentification_                                                                                                                                               |
| :--------------: | :--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------: |
|   **Objectif**   |                                                                                                                                               User can Sign up or Log in                                                                                                                                               |
| **Description**  | A Guest can Sign up by providing _full name_, _email_, _username_, _password_, and an _avatar_ will be generated automatically if signed up successfully. User can Log in by providing _username_/_email_, and _password_. Auth helps guarding data in database rather than in local storage so nothing happens to it. |
| **Constraints**  |                                                                                                                                                           -                                                                                                                                                            |
| **Dependencies** |                                                                                                                                                           -                                                                                                                                                            |
|   **Priority**   |                                                                                                                                                         Medium                                                                                                                                                         |

### Secondary Functionalities

|  Fonctionality   |          _FN0004: Show Interview Question_           |
| :--------------: | :--------------------------------------------------: |
|   **Objectif**   |          User can See an interview question          |
| **Description**  | Users can see interview questions with their answers |
| **Constraints**  |                          -                           |
| **Dependencies** |                          -                           |
|   **Priority**   |                         low                          |

|  Fonctionality   |                                                                     _FN0005: Choose Learning Mode_                                                                     |
| :--------------: | :--------------------------------------------------------------------------------------------------------------------------------------------------------------------: |
|   **Objectif**   |                                                                User can Choose a prefered Learning mode                                                                |
| **Description**  |                                              Learning Mode is how the user wants to learn and how much time is availlable                                              |
| **Constraints**  | - **Relaxed**(_Own Pace_): Continue without a timer - **Normal**(_Part-time_): Standard time for must of the users - **Hardcore**(_Immersive_): Half the standard time |
| **Dependencies** |                                                                                 FN0001                                                                                 |
|   **Priority**   |                                                                                  low                                                                                   |

|  Fonctionality   |                              _FN0006: Search Resources_                              |
| :--------------: | :----------------------------------------------------------------------------------: |
|   **Objectif**   |                            User can search for resources                             |
| **Description**  | User can search for a resource via tags, keywords, or any information regarding them |
| **Constraints**  |                                          -                                           |
| **Dependencies** |                                        FN0002                                        |
|   **Priority**   |                                         low                                          |

## ACCESSIBILITY

For the best user experience the use of semantic Html5 is a must, also with the usage of best practice and Aria attributes.

## BROWSER AND DEVICE SUPPORT

- Device support: most of the devices used including **Desktop**, **Tablet**, and **Mobile** devices.
- Browser support: most of the browsers excluding IE as it's deprecated.
- Features support: most of the latest features are supported thanks to Babel, PostCSS, AutoPrefixer and other packages.

## HOSTING

- Back-end - PHP API - **Heroku**
  - PHP
  - Apache
  - Composer
  - ClearDB MySQL
- Frontend - React UI - **Vercel**
  - Nodejs
  - Yarn
  - Vite

## ONGOING SUPPORT AND MAINTENANCE

For staying up to date with the industry we'll need to:

- Add new features
- Fix bugs and issues
- Change or tweak the UI
- Update the roadmaps we provide
- Add new content in a timely manner

## ASSUMPTIONS

As I'm the only one working on the project it's a given that I'll be responsible for all the tasks, which include but not limited to:

- Content addition
- Design and layout customisation options
- Migrating the site to the live server
- Ongoing maintenance
- SEO
- Hosting

## MILESTONES

- [x] Specifications - This document
- [x] Modelization
- [x] Database
- [x] Wireframes
- [x] Designs
- [x] Back-end - API Development
  - [x] Roadmaps endpoint
  - [x] Resources endpoint
  - [x] Interview Questions endpoint
  - [x] Auth endpoint
- [ ] Front-end - UI Development
  - [ ] Homepage
  - [ ] Login/Signup
- [ ] Release v1.0

## DEADLINES

| Phase          |     Deadline     |
| :------------- | :--------------: |
| Specification  | March 21st, 2022 |
| Modelization   |      2 Days      |
| Database       |      3 Days      |
| Wireframes     |      2 Days      |
| Designs        |      7 Days      |
| Back-end - API |      3 Days      |
| Front-end - UI |     15 Days      |
| Release v1.0   | June 29th, 2022  |

## Conclusion & Perspectives

I tried my best to outline the project in a way that it's easy to understand and built it with scalability in mind so that it can be improved with more features and better user experience over time. And as this project is the starting point for my own Explorer project, I'll be adding more features and improving the UI and UX even after turning it into a full-fledged project.

There are many features that I have in mind to add, but I'll be adding them gradually as I progress more in the development of the project.
