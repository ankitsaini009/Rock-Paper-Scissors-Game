let userScore = 0;
let compscore = 0;

//Moduler Way of Programing 

const choice = document.querySelectorAll('.choice');
const mymsg = document.querySelector('#msg');

const getuserscore = document.querySelector('#user-score');
const getcomscore = document.querySelector('#comp-score');

const gencompchoice = () => {
    const option = ['rock', 'paper', 'scissors'];
    const randidx = Math.floor(Math.random() * 3);
    return option[randidx];
}

const drawGame = () => {
    console.log('Game Was Draw');
    mymsg.innerText = 'Game Was Draw Play Again.';
    mymsg.style.backgroundColor = 'Yellow';
    mymsg.style.color = 'black';
}

const showwinner = (userWin) => {
    if (userWin) {
        userScore++;
        getuserscore.innerText = userScore;
        console.log('You Win!');
        mymsg.innerText = 'You Win!';
        mymsg.style.backgroundColor = 'green';
    } else {
        compscore++;
        getcomscore.innerText = compscore;
        console.log('You lose');
        mymsg.innerText = 'You lose.';
        mymsg.style.backgroundColor = 'Red';
    }
}

const playgame = (userchoice) => {
    console.log('user choice', userchoice)

    const comchoice = gencompchoice();
    console.log('CompChoice is ', comchoice);

    if (userchoice === comchoice) {
        //Draw Game
        drawGame();

    } else {
        let userWin = true;

        if (userchoice === 'rock') {
            // paper scissors
            userWin = comchoice === 'paper' ? false : true;
        } else if (userchoice === 'paper') {
            // rock scissors
            userWin = comchoice === 'scissors' ? false : true;
        } else {
            // paper rock
            userWin = comchoice === 'rock' ? false : true;
        }

        showwinner(userWin);
    }
}


choice.forEach((choice) => {

    choice.addEventListener('click', () => {
        const userchoice = choice.getAttribute('id');
        playgame(userchoice);
    })
});